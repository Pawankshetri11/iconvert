<?php
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\UsageLog;
use Illuminate\Support\Facades\Auth;
use Spatie\PdfToText\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

// Handle file upload and conversion
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_FILES['files']) || empty($_FILES['files'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No files uploaded']);
    exit;
}

$conversionType = $_POST['conversion_type'] ?? 'html-to-pdf';

// Check user authentication and subscription
$user = Auth::user();
$isGuest = !$user;

if ($isGuest) {
    // Guest user - check daily limit (3 conversions per day per IP)
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $cacheKey = 'guest_conversions_' . $ip . '_' . date('Y-m-d');
    $guestConversions = \Illuminate\Support\Facades\Cache::get($cacheKey, 0);

    if ($guestConversions >= 3) {
        http_response_code(429);
        echo json_encode(['error' => 'Daily limit exceeded for guests. Please register for unlimited access.']);
        exit;
    }
} else {
    // Registered user - check subscription access
    if (!$user->hasAddonAccess('pdf-converter')) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied. Upgrade your plan to use PDF converter.']);
        exit;
    }

    // Check conversion limits
    $remainingConversions = $user->getRemainingConversions();
    if ($remainingConversions !== -1 && $remainingConversions <= 0) {
        http_response_code(429);
        echo json_encode(['error' => 'Conversion limit exceeded. Upgrade your plan for more conversions.']);
        exit;
    }
}

$files = $_FILES['files'];
$uploadedFiles = [];

if (is_array($files['name'])) {
    // Multiple files
    for ($i = 0; $i < count($files['name']); $i++) {
        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            $uploadedFiles[] = [
                'name' => $files['name'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'type' => $files['type'][$i],
                'size' => $files['size'][$i]
            ];
        }
    }
} else {
    // Single file
    if ($files['error'] === UPLOAD_ERR_OK) {
        $uploadedFiles[] = [
            'name' => $files['name'],
            'tmp_name' => $files['tmp_name'],
            'type' => $files['type'],
            'size' => $files['size']
        ];
    }
}

if (empty($uploadedFiles)) {
    http_response_code(400);
    echo json_encode(['error' => 'No valid files uploaded']);
    exit;
}

// Process files based on conversion type
switch ($conversionType) {
    case 'html-to-pdf':
        processHtmlToPdf($uploadedFiles, $user, $isGuest);
        break;
    case 'pdf-to-word':
        processPdfToWord($uploadedFiles, $user, $isGuest);
        break;
    case 'pdf-to-text':
        processPdfToText($uploadedFiles, $user, $isGuest);
        break;
    case 'images-to-pdf':
        processImagesToPdf($uploadedFiles, $user, $isGuest);
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unsupported conversion type']);
        exit;
}

// For multiple files, create a ZIP (simplified - just return JSON for now)
if (count($uploadedFiles) > 1) {
    echo json_encode([
        'success' => true,
        'message' => 'Multiple files would be converted to PDFs and zipped',
        'files' => array_map(function($file) {
            return pathinfo($file['name'], PATHINFO_FILENAME) . '.pdf';
        }, $uploadedFiles)
    ]);
}

function processHtmlToPdf($files, $user, $isGuest) {
    $dompdf = new Dompdf();
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf->setOptions($options);

    foreach ($files as $file) {
        $content = file_get_contents($file['tmp_name']);
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $htmlContent = convertToHtml($content, $fileExtension, $file['name']);

        if ($htmlContent) {
            $dompdf->loadHtml($htmlContent);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $pdfOutput = $dompdf->output();
            $pdfFilename = pathinfo($file['name'], PATHINFO_FILENAME) . '.pdf';

            // Log successful conversion
            if ($isGuest) {
                // Increment guest conversion counter
                $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
                $cacheKey = 'guest_conversions_' . $ip . '_' . date('Y-m-d');
                \Illuminate\Support\Facades\Cache::increment($cacheKey, 1);
            } else {
                UsageLog::logSuccess(
                    $user->id,
                    'pdf-converter',
                    'convert',
                    'html-to-pdf',
                    [
                        'input_format' => $fileExtension,
                        'output_filename' => $pdfFilename,
                        'file_size' => strlen($pdfOutput)
                    ]
                );
            }

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $pdfFilename . '"');
            header('Content-Length: ' . strlen($pdfOutput));
            echo $pdfOutput;
            exit;
        }
    }
}

function processPdfToWord($files, $user, $isGuest) {
    foreach ($files as $file) {
        if (strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) !== 'pdf') {
            continue;
        }

        try {
            // Extract text from PDF
            $text = Pdf::getText($file['tmp_name']);

            // Create Word document
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            $section->addText($text);

            // Save to temporary file
            $wordFilename = pathinfo($file['name'], PATHINFO_FILENAME) . '.docx';
            $tempFile = tempnam(sys_get_temp_dir(), 'word_') . '.docx';

            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($tempFile);

            $wordContent = file_get_contents($tempFile);
            unlink($tempFile); // Clean up temp file

            // Log successful conversion
            if ($isGuest) {
                // Increment guest conversion counter
                $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
                $cacheKey = 'guest_conversions_' . $ip . '_' . date('Y-m-d');
                \Illuminate\Support\Facades\Cache::increment($cacheKey, 1);
            } else {
                UsageLog::logSuccess(
                    $user->id,
                    'pdf-converter',
                    'convert',
                    'pdf-to-word',
                    [
                        'input_filename' => $file['name'],
                        'output_filename' => $wordFilename,
                        'file_size' => strlen($wordContent)
                    ]
                );
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment; filename="' . $wordFilename . '"');
            header('Content-Length: ' . strlen($wordContent));
            echo $wordContent;
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to convert PDF to Word: ' . $e->getMessage()]);
            exit;
        }
    }
}

function processPdfToText($files, $user, $isGuest) {
    foreach ($files as $file) {
        if (strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) !== 'pdf') {
            continue;
        }

        try {
            // Extract text from PDF
            $text = Pdf::getText($file['tmp_name']);
            $textFilename = pathinfo($file['name'], PATHINFO_FILENAME) . '.txt';

            // Log successful conversion
            if ($isGuest) {
                // Increment guest conversion counter
                $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
                $cacheKey = 'guest_conversions_' . $ip . '_' . date('Y-m-d');
                \Illuminate\Support\Facades\Cache::increment($cacheKey, 1);
            } else {
                UsageLog::logSuccess(
                    $user->id,
                    'pdf-converter',
                    'convert',
                    'pdf-to-text',
                    [
                        'input_filename' => $file['name'],
                        'output_filename' => $textFilename,
                        'file_size' => strlen($text)
                    ]
                );
            }

            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="' . $textFilename . '"');
            header('Content-Length: ' . strlen($text));
            echo $text;
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to extract text from PDF: ' . $e->getMessage()]);
            exit;
        }
    }
}

function processImagesToPdf($files, $user, $isGuest) {
    $dompdf = new Dompdf();
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf->setOptions($options);

    $htmlContent = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Images to PDF</title>';
    $htmlContent .= '<style>body { margin: 0; padding: 20px; } img { max-width: 100%; height: auto; margin-bottom: 20px; page-break-after: always; }</style>';
    $htmlContent .= '</head><body>';

    foreach ($files as $file) {
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'webp'])) {
            $base64Image = base64_encode(file_get_contents($file['tmp_name']));
            $mimeType = mime_content_type($file['tmp_name']);
            $htmlContent .= '<img src="data:' . $mimeType . ';base64,' . $base64Image . '" alt="' . htmlspecialchars($file['name']) . '">';
        }
    }

    $htmlContent .= '</body></html>';

    $dompdf->loadHtml($htmlContent);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfOutput = $dompdf->output();
    $pdfFilename = 'images_combined.pdf';

    // Log successful conversion
    if ($isGuest) {
        // Increment guest conversion counter
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $cacheKey = 'guest_conversions_' . $ip . '_' . date('Y-m-d');
        \Illuminate\Support\Facades\Cache::increment($cacheKey, 1);
    } else {
        UsageLog::logSuccess(
            $user->id,
            'pdf-converter',
            'convert',
            'images-to-pdf',
            [
                'image_count' => count($files),
                'output_filename' => $pdfFilename,
                'file_size' => strlen($pdfOutput)
            ]
        );
    }

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $pdfFilename . '"');
    header('Content-Length: ' . strlen($pdfOutput));
    echo $pdfOutput;
    exit;
}

function convertToHtml($content, $extension, $filename) {
    $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>' . htmlspecialchars($filename) . '</title>';
    $html .= '<style>body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }</style>';
    $html .= '</head><body>';

    switch ($extension) {
        case 'html':
        case 'htm':
            // For HTML files, wrap in a div to isolate content
            $html .= '<div>' . $content . '</div>';
            break;

        case 'txt':
            // Convert text to HTML with line breaks
            $html .= '<pre>' . htmlspecialchars($content) . '</pre>';
            break;

        default:
            // For unsupported formats, show error
            $html .= '<h1>Unsupported File Format</h1>';
            $html .= '<p>The file format "' . htmlspecialchars($extension) . '" is not currently supported for PDF conversion.</p>';
            $html .= '<p>Supported formats: HTML, TXT</p>';
            break;
    }

    $html .= '</body></html>';
    return $html;
}