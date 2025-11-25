<?php
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\UsageLog;
use Illuminate\Support\Facades\Auth;
use Spatie\PdfToText\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory as PresentationIOFactory;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader\PageBoundaries;

// Handle PDF Tools processing
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$tool = $_POST['tool'] ?? '';
$user = Auth::user();
$isGuest = !$user;

// For now, allow all tools for guests to test functionality
// TODO: Implement proper guest restrictions later
// if ($isGuest && !in_array($tool, ['html-to-pdf', 'images-to-pdf', 'text-to-pdf'])) {
//     // Guests can only use basic creation tools
//     http_response_code(401);
//     echo json_encode(['error' => 'Authentication required for this tool']);
//     exit;
// }

if (!$isGuest) {
    // Check subscription limits for registered users
    // Temporarily disabled for testing
    // if (!$user->hasAddonAccess('pdf-converter')) {
    //     http_response_code(403);
    //     echo json_encode(['error' => 'Access denied. Upgrade your plan to use PDF tools.']);
    //     exit;
    // }

    // $remainingConversions = $user->getRemainingConversions();
    // if ($remainingConversions !== -1 && $remainingConversions <= 0) {
    //     http_response_code(429);
    //     echo json_encode(['error' => 'Conversion limit exceeded. Upgrade your plan for more conversions.']);
    //     exit;
    // }
} else {
    // Guest limits - 3 conversions per day
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $cacheKey = 'guest_conversions_' . $ip . '_' . date('Y-m-d');
    $guestConversions = \Illuminate\Support\Facades\Cache::get($cacheKey, 0);

    if ($guestConversions >= 3) {
        http_response_code(429);
        echo json_encode(['error' => 'Daily limit exceeded for guests. Please register for unlimited access.']);
        exit;
    }
}

// Process based on tool
switch ($tool) {
    // PDF Converters
    case 'pdf-to-word':
        processPdfToWord($user, $isGuest);
        break;
    case 'pdf-to-excel':
        processPdfToExcel($user, $isGuest);
        break;
    case 'pdf-to-ppt':
        processPdfToPowerPoint($user, $isGuest);
        break;
    case 'pdf-to-text':
        processPdfToText($user, $isGuest);
        break;
    case 'pdf-to-html':
        processPdfToHtml($user, $isGuest);
        break;
    case 'pdf-to-images':
        processPdfToImages($user, $isGuest);
        break;

    // Create PDFs
    case 'word-to-pdf':
        processWordToPdf($user, $isGuest);
        break;
    case 'excel-to-pdf':
        processExcelToPdf($user, $isGuest);
        break;
    case 'ppt-to-pdf':
        processPowerPointToPdf($user, $isGuest);
        break;
    case 'html-to-pdf':
        processHtmlToPdf($user, $isGuest);
        break;
    case 'images-to-pdf':
        processImagesToPdf($user, $isGuest);
        break;
    case 'text-to-pdf':
        processTextToPdf($user, $isGuest);
        break;

    // PDF Editor
    case 'pdf-editor':
        processPdfEditor($user, $isGuest);
        break;
    case 'pdf-rotate':
        processPdfRotate($user, $isGuest);
        break;
    case 'pdf-watermark':
        processPdfWatermark($user, $isGuest);
        break;
    case 'pdf-protect':
        processPdfProtect($user, $isGuest);
        break;
    case 'pdf-unlock':
        processPdfUnlock($user, $isGuest);
        break;

    // PDF Utilities
    case 'pdf-merge':
        processPdfMerge($user, $isGuest);
        break;
    case 'pdf-split':
        processPdfSplit($user, $isGuest);
        break;
    case 'pdf-compress':
        processPdfCompress($user, $isGuest);
        break;
    case 'pdf-repair':
        processPdfRepair($user, $isGuest);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown tool']);
        exit;
}

// Tool implementations

function processPdfToWord($user, $isGuest) {
    if (!isset($_FILES['files']) || empty($_FILES['files'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No files uploaded']);
        exit;
    }

    $files = $_FILES['files'];
    $uploadedFile = $files['tmp_name'];

    try {
        // Extract text from PDF
        $text = Pdf::getText($uploadedFile);

        // Create Word document
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText($text);

        // Save to temporary file
        $wordFilename = pathinfo($files['name'], PATHINFO_FILENAME) . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), 'word_') . '.docx';

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        $wordContent = file_get_contents($tempFile);
        unlink($tempFile);

        // Log conversion
        logConversion($user, $isGuest, 'pdf-converter', 'pdf-to-word', $files['name'], $wordFilename, strlen($wordContent));

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

function processPdfToExcel($user, $isGuest) {
    if (!isset($_FILES['files']) || empty($_FILES['files'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No files uploaded']);
        exit;
    }

    $files = $_FILES['files'];
    $uploadedFile = $files['tmp_name'];

    try {
        // Extract text from PDF
        $text = Pdf::getText($uploadedFile);

        // Create Excel spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Extracted Text from PDF');
        $sheet->setCellValue('A2', $text);

        // Save to temporary file
        $excelFilename = pathinfo($files['name'], PATHINFO_FILENAME) . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'excel_') . '.xlsx';

        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        $excelContent = file_get_contents($tempFile);
        unlink($tempFile);

        // Log conversion
        logConversion($user, $isGuest, 'pdf-converter', 'pdf-to-excel', $files['name'], $excelFilename, strlen($excelContent));

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $excelFilename . '"');
        header('Content-Length: ' . strlen($excelContent));
        echo $excelContent;
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to convert PDF to Excel: ' . $e->getMessage()]);
        exit;
    }
}

function processPdfToPowerPoint($user, $isGuest) {
    if (!isset($_FILES['files']) || empty($_FILES['files'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No files uploaded']);
        exit;
    }

    $files = $_FILES['files'];
    $uploadedFile = $files['tmp_name'];

    try {
        // Extract text from PDF
        $text = Pdf::getText($uploadedFile);

        // Create PowerPoint presentation
        $presentation = new PhpPresentation();
        $slide = $presentation->getActiveSlide();
        $shape = $slide->createRichTextShape();
        $shape->setHeight(300);
        $shape->setWidth(600);
        $shape->setOffsetX(170);
        $shape->setOffsetY(180);
        $textRun = $shape->createTextRun($text);

        // Save to temporary file
        $pptFilename = pathinfo($files['name'], PATHINFO_FILENAME) . '.pptx';
        $tempFile = tempnam(sys_get_temp_dir(), 'ppt_') . '.pptx';

        $writer = PresentationIOFactory::createWriter($presentation);
        $writer->save($tempFile);

        $pptContent = file_get_contents($tempFile);
        unlink($tempFile);

        // Log conversion
        logConversion($user, $isGuest, 'pdf-converter', 'pdf-to-ppt', $files['name'], $pptFilename, strlen($pptContent));

        header('Content-Type: application/vnd.openxmlformats-officedocument.presentationml.presentation');
        header('Content-Disposition: attachment; filename="' . $pptFilename . '"');
        header('Content-Length: ' . strlen($pptContent));
        echo $pptContent;
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to convert PDF to PowerPoint: ' . $e->getMessage()]);
        exit;
    }
}

function processPdfToText($user, $isGuest) {
    if (!isset($_FILES['files']) || empty($_FILES['files'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No files uploaded']);
        exit;
    }

    $files = $_FILES['files'];
    $uploadedFile = $files['tmp_name'];

    try {
        // Extract text from PDF
        $text = Pdf::getText($uploadedFile);
        $textFilename = pathinfo($files['name'], PATHINFO_FILENAME) . '.txt';

        // Log conversion
        logConversion($user, $isGuest, 'pdf-converter', 'pdf-to-text', $files['name'], $textFilename, strlen($text));

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

function processPdfToHtml($user, $isGuest) {
    if (!isset($_FILES['files']) || empty($_FILES['files'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No files uploaded']);
        exit;
    }

    $files = $_FILES['files'];
    $uploadedFile = $files['tmp_name'];

    try {
        // Extract text from PDF
        $text = Pdf::getText($uploadedFile);
        $htmlContent = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>PDF Content</title></head><body><pre>' . htmlspecialchars($text) . '</pre></body></html>';
        $htmlFilename = pathinfo($files['name'], PATHINFO_FILENAME) . '.html';

        // Log conversion
        logConversion($user, $isGuest, 'pdf-converter', 'pdf-to-html', $files['name'], $htmlFilename, strlen($htmlContent));

        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="' . $htmlFilename . '"');
        header('Content-Length: ' . strlen($htmlContent));
        echo $htmlContent;
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to convert PDF to HTML: ' . $e->getMessage()]);
        exit;
    }
}

function processPdfToImages($user, $isGuest) {
    if (!isset($_FILES['files']) || empty($_FILES['files'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No files uploaded']);
        exit;
    }

    $files = $_FILES['files'];
    $uploadedFile = $files['tmp_name'];
    $imageFormat = $_POST['image-format'] ?? 'png';
    $resolution = (int)($_POST['resolution'] ?? 150);

    try {
        // Use ImageMagick or similar to convert PDF pages to images
        // For now, create a simple placeholder implementation
        $zipFilename = pathinfo($files['name'], PATHINFO_FILENAME) . '_images.zip';

        // Create a temporary directory for images
        $tempDir = sys_get_temp_dir() . '/pdf_images_' . uniqid();
        mkdir($tempDir);

        // Simulate creating image files (in real implementation, use proper PDF to image conversion)
        for ($i = 1; $i <= 3; $i++) { // Assume 3 pages for demo
            $imageContent = 'Simulated image content for page ' . $i; // Replace with actual image generation
            file_put_contents($tempDir . '/page_' . $i . '.' . $imageFormat, $imageContent);
        }

        // Create ZIP file
        $zip = new ZipArchive();
        $zipFile = tempnam(sys_get_temp_dir(), 'pdf_images_') . '.zip';
        $zip->open($zipFile, ZipArchive::CREATE);

        $files = scandir($tempDir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $zip->addFile($tempDir . '/' . $file, $file);
            }
        }
        $zip->close();

        // Clean up temp directory
        array_map('unlink', glob($tempDir . '/*'));
        rmdir($tempDir);

        $zipContent = file_get_contents($zipFile);
        unlink($zipFile);

        // Log conversion
        logConversion($user, $isGuest, 'pdf-converter', 'pdf-to-images', $files['name'], $zipFilename, strlen($zipContent));

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipFilename . '"');
        header('Content-Length: ' . strlen($zipContent));
        echo $zipContent;
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to convert PDF to images: ' . $e->getMessage()]);
        exit;
    }
}

function processWordToPdf($user, $isGuest) {
    if (!isset($_FILES['files']) || empty($_FILES['files'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No files uploaded']);
        exit;
    }

    $files = $_FILES['files'];
    $uploadedFiles = [];

    if (is_array($files['name'])) {
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
        $uploadedFiles[] = [
            'name' => $files['name'],
            'tmp_name' => $files['tmp_name'],
            'type' => $files['type'],
            'size' => $files['size']
        ];
    }

    try {
        $dompdf = new Dompdf();
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);

        foreach ($uploadedFiles as $file) {
            // Load Word document
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($file['tmp_name']);

            // Convert to HTML
            $htmlWriter = new \PhpOffice\PhpWord\Writer\HTML($phpWord);
            $htmlContent = $htmlWriter->getContent();

            // Add basic styling
            $styledHtml = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>' . htmlspecialchars($file['name']) . '</title>';
            $styledHtml .= '<style>body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }</style>';
            $styledHtml .= '</head><body>' . $htmlContent . '</body></html>';

            $dompdf->loadHtml($styledHtml);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $pdfOutput = $dompdf->output();
            $pdfFilename = pathinfo($file['name'], PATHINFO_FILENAME) . '.pdf';

            // Log conversion
            logConversion($user, $isGuest, 'pdf-converter', 'word-to-pdf', $file['name'], $pdfFilename, strlen($pdfOutput));

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $pdfFilename . '"');
            header('Content-Length: ' . strlen($pdfOutput));
            echo $pdfOutput;
            exit;
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to convert Word to PDF: ' . $e->getMessage()]);
        exit;
    }
}

function processExcelToPdf($user, $isGuest) {
    // Implementation for Excel to PDF conversion
    http_response_code(501);
    echo json_encode(['error' => 'Excel to PDF conversion not yet implemented']);
    exit;
}

function processPowerPointToPdf($user, $isGuest) {
    // Implementation for PowerPoint to PDF conversion
    http_response_code(501);
    echo json_encode(['error' => 'PowerPoint to PDF conversion not yet implemented']);
    exit;
}

function processHtmlToPdf($user, $isGuest) {
    if (!isset($_FILES['files']) || empty($_FILES['files'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No files uploaded']);
        exit;
    }

    $files = $_FILES['files'];
    $uploadedFiles = [];

    if (is_array($files['name'])) {
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
        $uploadedFiles[] = [
            'name' => $files['name'],
            'tmp_name' => $files['tmp_name'],
            'type' => $files['type'],
            'size' => $files['size']
        ];
    }

    $dompdf = new Dompdf();
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf->setOptions($options);

    foreach ($uploadedFiles as $file) {
        $content = file_get_contents($file['tmp_name']);
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $htmlContent = convertToHtml($content, $fileExtension, $file['name']);

        if ($htmlContent) {
            $dompdf->loadHtml($htmlContent);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $pdfOutput = $dompdf->output();
            $pdfFilename = pathinfo($file['name'], PATHINFO_FILENAME) . '.pdf';

            // Log conversion
            logConversion($user, $isGuest, 'pdf-converter', 'html-to-pdf', $file['name'], $pdfFilename, strlen($pdfOutput));

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $pdfFilename . '"');
            header('Content-Length: ' . strlen($pdfOutput));
            echo $pdfOutput;
            exit;
        }
    }
}

function processImagesToPdf($user, $isGuest) {
    if (!isset($_FILES['files']) || empty($_FILES['files'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No files uploaded']);
        exit;
    }

    $files = $_FILES['files'];
    $uploadedFiles = [];

    if (is_array($files['name'])) {
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
        $uploadedFiles[] = [
            'name' => $files['name'],
            'tmp_name' => $files['tmp_name'],
            'type' => $files['type'],
            'size' => $files['size']
        ];
    }

    $dompdf = new Dompdf();
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf->setOptions($options);

    $htmlContent = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Images to PDF</title>';
    $htmlContent .= '<style>body { margin: 0; padding: 20px; } img { max-width: 100%; height: auto; margin-bottom: 20px; page-break-after: always; }</style>';
    $htmlContent .= '</head><body>';

    foreach ($uploadedFiles as $file) {
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
    $pdfFilename = count($uploadedFiles) > 1 ? 'combined_images.pdf' : pathinfo($uploadedFiles[0]['name'], PATHINFO_FILENAME) . '.pdf';

    // Log conversion
    logConversion($user, $isGuest, 'pdf-converter', 'images-to-pdf', implode(', ', array_column($uploadedFiles, 'name')), $pdfFilename, strlen($pdfOutput));

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $pdfFilename . '"');
    header('Content-Length: ' . strlen($pdfOutput));
    echo $pdfOutput;
    exit;
}

function processTextToPdf($user, $isGuest) {
    if (!isset($_FILES['files']) || empty($_FILES['files'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No files uploaded']);
        exit;
    }

    $files = $_FILES['files'];
    $uploadedFiles = [];

    if (is_array($files['name'])) {
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
        $uploadedFiles[] = [
            'name' => $files['name'],
            'tmp_name' => $files['tmp_name'],
            'type' => $files['type'],
            'size' => $files['size']
        ];
    }

    $dompdf = new Dompdf();
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $dompdf->setOptions($options);

    foreach ($uploadedFiles as $file) {
        $content = file_get_contents($file['tmp_name']);
        $htmlContent = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>' . htmlspecialchars($file['name']) . '</title>';
        $htmlContent .= '<style>body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }</style>';
        $htmlContent .= '</head><body><pre>' . htmlspecialchars($content) . '</pre></body></html>';

        $dompdf->loadHtml($htmlContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfOutput = $dompdf->output();
        $pdfFilename = pathinfo($file['name'], PATHINFO_FILENAME) . '.pdf';

        // Log conversion
        logConversion($user, $isGuest, 'pdf-converter', 'text-to-pdf', $file['name'], $pdfFilename, strlen($pdfOutput));

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $pdfFilename . '"');
        header('Content-Length: ' . strlen($pdfOutput));
        echo $pdfOutput;
        exit;
    }
}

// Placeholder implementations for remaining tools
function processPdfEditor($user, $isGuest) {
    if (!isset($_FILES['files']) || empty($_FILES['files'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No files uploaded']);
        exit;
    }

    $files = $_FILES['files'];
    $uploadedFile = $files['tmp_name'];

    // For now, implement basic PDF editor functionality
    // This is a placeholder - in a full implementation, you'd use a library like TCPDF or similar
    // to add text, images, shapes, etc. to existing PDFs

    try {
        // Basic implementation: just return the original PDF for now
        // In a real implementation, you'd modify the PDF based on editor parameters

        $pdfContent = file_get_contents($uploadedFile);
        $pdfFilename = pathinfo($files['name'], PATHINFO_FILENAME) . '_edited.pdf';

        // Log conversion
        logConversion($user, $isGuest, 'pdf-converter', 'pdf-editor', $files['name'], $pdfFilename, strlen($pdfContent));

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $pdfFilename . '"');
        header('Content-Length: ' . strlen($pdfContent));
        echo $pdfContent;
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to edit PDF: ' . $e->getMessage()]);
        exit;
    }
}

function processPdfRotate($user, $isGuest) {
    if (!isset($_FILES['files']) || empty($_FILES['files'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No files uploaded']);
        exit;
    }

    $files = $_FILES['files'];
    $uploadedFile = $files['tmp_name'];
    $rotation = $_POST['rotation'] ?? '90° Clockwise';
    $pages = $_POST['pages'] ?? 'All Pages';

    try {
        $pdf = new Fpdi();

        // Get total pages
        $pageCount = $pdf->setSourceFile($uploadedFile);

        // Determine rotation angle
        $angle = 0;
        switch ($rotation) {
            case '90° Clockwise':
                $angle = 90;
                break;
            case '90° Counter-clockwise':
                $angle = -90;
                break;
            case '180°':
                $angle = 180;
                break;
        }

        // Determine which pages to rotate
        $pagesToRotate = [];
        switch ($pages) {
            case 'All Pages':
                $pagesToRotate = range(1, $pageCount);
                break;
            case 'Even Pages':
                $pagesToRotate = range(2, $pageCount, 2);
                break;
            case 'Odd Pages':
                $pagesToRotate = range(1, $pageCount, 2);
                break;
            case 'Custom Range':
                // For simplicity, rotate all pages if custom range is selected
                $pagesToRotate = range(1, $pageCount);
                break;
        }

        // Process each page
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);

            $size = $pdf->getTemplateSize($templateId);

            if (in_array($pageNo, $pagesToRotate)) {
                // Rotate this page
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height'], true);
                $pdf->Rotate($angle);
            } else {
                // Keep original orientation
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height'], true);
            }
        }

        $pdfOutput = $pdf->Output('S');
        $pdfFilename = pathinfo($files['name'], PATHINFO_FILENAME) . '_rotated.pdf';

        // Log conversion
        logConversion($user, $isGuest, 'pdf-converter', 'pdf-rotate', $files['name'], $pdfFilename, strlen($pdfOutput));

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $pdfFilename . '"');
        header('Content-Length: ' . strlen($pdfOutput));
        echo $pdfOutput;
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to rotate PDF: ' . $e->getMessage()]);
        exit;
    }
}

function processPdfWatermark($user, $isGuest) {
    if (!isset($_FILES['files']) || empty($_FILES['files'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No files uploaded']);
        exit;
    }

    $files = $_FILES['files'];
    $uploadedFile = $files['tmp_name'];
    $watermarkText = $_POST['watermark-text'] ?? '';
    $position = $_POST['position'] ?? 'Center';
    $color = $_POST['color'] ?? '#000000';
    $opacity = (int)($_POST['opacity'] ?? 50);

    if (empty($watermarkText)) {
        http_response_code(400);
        echo json_encode(['error' => 'Watermark text is required']);
        exit;
    }

    try {
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($uploadedFile);

        // Convert hex color to RGB
        $color = ltrim($color, '#');
        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height'], true);

            // Set watermark properties
            $pdf->SetAlpha($opacity / 100); // Set transparency
            $pdf->SetTextColor($r, $g, $b);
            $pdf->SetFont('Arial', 'B', 50);

            // Calculate position
            $textWidth = $pdf->GetStringWidth($watermarkText);
            $textHeight = 50; // Approximate height for 50pt font

            switch ($position) {
                case 'Center':
                    $x = ($size['width'] - $textWidth) / 2;
                    $y = ($size['height'] + $textHeight) / 2;
                    break;
                case 'Top Left':
                    $x = 20;
                    $y = $textHeight + 20;
                    break;
                case 'Top Right':
                    $x = $size['width'] - $textWidth - 20;
                    $y = $textHeight + 20;
                    break;
                case 'Bottom Left':
                    $x = 20;
                    $y = $size['height'] - 20;
                    break;
                case 'Bottom Right':
                    $x = $size['width'] - $textWidth - 20;
                    $y = $size['height'] - 20;
                    break;
                case 'Diagonal':
                    $pdf->Rotate(45, $size['width']/2, $size['height']/2);
                    $x = ($size['width'] - $textWidth) / 2;
                    $y = ($size['height'] + $textHeight) / 2;
                    break;
                default:
                    $x = ($size['width'] - $textWidth) / 2;
                    $y = ($size['height'] + $textHeight) / 2;
            }

            $pdf->Text($x, $y, $watermarkText);
            $pdf->SetAlpha(1); // Reset transparency
        }

        $pdfOutput = $pdf->Output('S');
        $pdfFilename = pathinfo($files['name'], PATHINFO_FILENAME) . '_watermarked.pdf';

        // Log conversion
        logConversion($user, $isGuest, 'pdf-converter', 'pdf-watermark', $files['name'], $pdfFilename, strlen($pdfOutput));

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $pdfFilename . '"');
        header('Content-Length: ' . strlen($pdfOutput));
        echo $pdfOutput;
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to add watermark to PDF: ' . $e->getMessage()]);
        exit;
    }
}

function processPdfProtect($user, $isGuest) {
    if (!isset($_FILES['files']) || empty($_FILES['files'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No files uploaded']);
        exit;
    }

    $files = $_FILES['files'];
    $uploadedFile = $files['tmp_name'];
    $userPassword = $_POST['user-password'] ?? '';
    $ownerPassword = $_POST['owner-password'] ?? '';
    $allowPrint = isset($_POST['print']) && $_POST['print'] === '1';
    $allowCopy = isset($_POST['copy']) && $_POST['copy'] === '1';
    $allowModify = isset($_POST['modify']) && $_POST['modify'] === '1';

    try {
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($uploadedFile);

        // Set protection permissions
        $permissions = 0;
        if ($allowPrint) $permissions |= \setasign\Fpdi\PdfParser\PdfParser::PERMISSION_PRINT;
        if ($allowCopy) $permissions |= \setasign\Fpdi\PdfParser\PdfParser::PERMISSION_COPY;
        if ($allowModify) $permissions |= \setasign\Fpdi\PdfParser\PdfParser::PERMISSION_MODIFY;

        // Copy all pages
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height'], true);
        }

        // Set protection
        $pdf->SetProtection($permissions, $userPassword, $ownerPassword);

        $pdfOutput = $pdf->Output('S');
        $pdfFilename = pathinfo($files['name'], PATHINFO_FILENAME) . '_protected.pdf';

        // Log conversion
        logConversion($user, $isGuest, 'pdf-converter', 'pdf-protect', $files['name'], $pdfFilename, strlen($pdfOutput));

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $pdfFilename . '"');
        header('Content-Length: ' . strlen($pdfOutput));
        echo $pdfOutput;
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to protect PDF: ' . $e->getMessage()]);
        exit;
    }
}

function processPdfUnlock($user, $isGuest) {
    if (!isset($_FILES['files']) || empty($_FILES['files'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No files uploaded']);
        exit;
    }

    $files = $_FILES['files'];
    $uploadedFile = $files['tmp_name'];
    $password = $_POST['password'] ?? '';

    if (empty($password)) {
        http_response_code(400);
        echo json_encode(['error' => 'Password is required to unlock PDF']);
        exit;
    }

    try {
        // Try to open the PDF with the provided password
        $pdf = new Fpdi();
        $pdf->setSourceFile($uploadedFile);

        // If we get here without exception, the PDF is either not password-protected or password is correct
        $pageCount = $pdf->setSourceFile($uploadedFile);

        // Create a new PDF without password protection
        $newPdf = new Fpdi();

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $newPdf->importPage($pageNo);
            $size = $newPdf->getTemplateSize($templateId);

            $newPdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $newPdf->useTemplate($templateId, 0, 0, $size['width'], $size['height'], true);
        }

        $pdfOutput = $newPdf->Output('S');
        $pdfFilename = pathinfo($files['name'], PATHINFO_FILENAME) . '_unlocked.pdf';

        // Log conversion
        logConversion($user, $isGuest, 'pdf-converter', 'pdf-unlock', $files['name'], $pdfFilename, strlen($pdfOutput));

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $pdfFilename . '"');
        header('Content-Length: ' . strlen($pdfOutput));
        echo $pdfOutput;
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to unlock PDF. Please check the password and try again.']);
        exit;
    }
}

function processPdfMerge($user, $isGuest) {
    if (!isset($_FILES['files']) || empty($_FILES['files'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No files uploaded']);
        exit;
    }

    $files = $_FILES['files'];
    $uploadedFiles = [];

    if (is_array($files['name'])) {
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $uploadedFiles[] = [
                    'name' => $files['name'][$i],
                    'tmp_name' => $files['tmp_name'][$i]
                ];
            }
        }
    } else {
        $uploadedFiles[] = [
            'name' => $files['name'],
            'tmp_name' => $files['tmp_name']
        ];
    }

    if (count($uploadedFiles) < 2) {
        http_response_code(400);
        echo json_encode(['error' => 'At least 2 PDF files are required for merging']);
        exit;
    }

    try {
        $pdf = new Fpdi();

        foreach ($uploadedFiles as $file) {
            $pageCount = $pdf->setSourceFile($file['tmp_name']);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height'], true);
            }
        }

        $pdfOutput = $pdf->Output('S');
        $pdfFilename = 'merged_document.pdf';

        // Log conversion
        $inputFilenames = array_column($uploadedFiles, 'name');
        logConversion($user, $isGuest, 'pdf-converter', 'pdf-merge', implode(', ', $inputFilenames), $pdfFilename, strlen($pdfOutput));

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $pdfFilename . '"');
        header('Content-Length: ' . strlen($pdfOutput));
        echo $pdfOutput;
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to merge PDFs: ' . $e->getMessage()]);
        exit;
    }
}

function processPdfSplit($user, $isGuest) {
    if (!isset($_FILES['files']) || empty($_FILES['files'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No files uploaded']);
        exit;
    }

    $files = $_FILES['files'];
    $uploadedFile = $files['tmp_name'];
    $splitMethod = $_POST['split-method'] ?? 'By Page Range';
    $pageRanges = $_POST['page-ranges'] ?? '1-5';

    try {
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($uploadedFile);

        // Create temporary directory for split files
        $tempDir = sys_get_temp_dir() . '/pdf_split_' . uniqid();
        mkdir($tempDir);

        $splitFiles = [];

        if ($splitMethod === 'By Page Range') {
            // Parse page ranges (e.g., "1-5,8,10-15")
            $ranges = explode(',', $pageRanges);
            $pagesToExtract = [];

            foreach ($ranges as $range) {
                $range = trim($range);
                if (strpos($range, '-') !== false) {
                    list($start, $end) = explode('-', $range);
                    $start = (int)trim($start);
                    $end = (int)trim($end);
                    for ($i = $start; $i <= $end; $i++) {
                        if ($i <= $pageCount) {
                            $pagesToExtract[] = $i;
                        }
                    }
                } else {
                    $page = (int)trim($range);
                    if ($page <= $pageCount) {
                        $pagesToExtract[] = $page;
                    }
                }
            }

            if (!empty($pagesToExtract)) {
                $newPdf = new Fpdi();
                foreach ($pagesToExtract as $pageNo) {
                    $templateId = $newPdf->importPage($pageNo);
                    $size = $newPdf->getTemplateSize($templateId);
                    $newPdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $newPdf->useTemplate($templateId, 0, 0, $size['width'], $size['height'], true);
                }

                $outputFile = $tempDir . '/split_pages.pdf';
                $newPdf->Output($outputFile, 'F');
                $splitFiles[] = $outputFile;
            }
        } elseif ($splitMethod === 'Extract Single Pages') {
            // Extract each page as separate PDF
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $newPdf = new Fpdi();
                $templateId = $newPdf->importPage($pageNo);
                $size = $newPdf->getTemplateSize($templateId);
                $newPdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $newPdf->useTemplate($templateId, 0, 0, $size['width'], $size['height'], true);

                $outputFile = $tempDir . '/page_' . $pageNo . '.pdf';
                $newPdf->Output($outputFile, 'F');
                $splitFiles[] = $outputFile;
            }
        }

        // Create ZIP file with split PDFs
        $zip = new ZipArchive();
        $zipFile = tempnam(sys_get_temp_dir(), 'pdf_split_') . '.zip';
        $zip->open($zipFile, ZipArchive::CREATE);

        foreach ($splitFiles as $index => $file) {
            $filename = basename($file);
            $zip->addFile($file, $filename);
        }
        $zip->close();

        // Clean up temp files
        foreach ($splitFiles as $file) {
            unlink($file);
        }
        rmdir($tempDir);

        $zipContent = file_get_contents($zipFile);
        unlink($zipFile);

        $zipFilename = pathinfo($files['name'], PATHINFO_FILENAME) . '_split.zip';

        // Log conversion
        logConversion($user, $isGuest, 'pdf-converter', 'pdf-split', $files['name'], $zipFilename, strlen($zipContent));

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipFilename . '"');
        header('Content-Length: ' . strlen($zipContent));
        echo $zipContent;
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to split PDF: ' . $e->getMessage()]);
        exit;
    }
}

function processPdfCompress($user, $isGuest) {
    if (!isset($_FILES['files']) || empty($_FILES['files'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No files uploaded']);
        exit;
    }

    $files = $_FILES['files'];
    $uploadedFile = $files['tmp_name'];
    $compressionLevel = $_POST['compression-level'] ?? 'Medium';

    try {
        // For basic compression, we'll use FPDI to recreate the PDF with optimized settings
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($uploadedFile);

        // Set compression options based on level
        switch ($compressionLevel) {
            case 'Low (Better Quality)':
                $pdf->SetCompression(false); // No compression for better quality
                break;
            case 'Medium':
                $pdf->SetCompression(true);
                $pdf->SetCompressionQuality(75); // Medium quality
                break;
            case 'High (Smaller Size)':
                $pdf->SetCompression(true);
                $pdf->SetCompressionQuality(50); // Lower quality for smaller size
                break;
            case 'Maximum':
                $pdf->SetCompression(true);
                $pdf->SetCompressionQuality(25); // Very low quality for maximum compression
                break;
        }

        // Copy all pages
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height'], true);
        }

        $pdfOutput = $pdf->Output('S');
        $pdfFilename = pathinfo($files['name'], PATHINFO_FILENAME) . '_compressed.pdf';

        // Log conversion
        logConversion($user, $isGuest, 'pdf-converter', 'pdf-compress', $files['name'], $pdfFilename, strlen($pdfOutput));

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $pdfFilename . '"');
        header('Content-Length: ' . strlen($pdfOutput));
        echo $pdfOutput;
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to compress PDF: ' . $e->getMessage()]);
        exit;
    }
}

function processPdfRepair($user, $isGuest) {
    http_response_code(501);
    echo json_encode(['error' => 'PDF Repair not yet implemented']);
    exit;
}

// Helper functions
function logConversion($user, $isGuest, $addonSlug, $action, $inputFilename, $outputFilename, $fileSize) {
    if ($isGuest) {
        // Increment guest conversion counter
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $cacheKey = 'guest_conversions_' . $ip . '_' . date('Y-m-d');
        \Illuminate\Support\Facades\Cache::increment($cacheKey, 1);
    } else {
        UsageLog::logSuccess(
            $user->id,
            $addonSlug,
            $action,
            $action,
            [
                'input_filename' => $inputFilename,
                'output_filename' => $outputFilename,
                'file_size' => $fileSize
            ]
        );
    }
}

function convertToHtml($content, $extension, $filename) {
    $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>' . htmlspecialchars($filename) . '</title>';
    $html .= '<style>body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }</style>';
    $html .= '</head><body>';

    switch ($extension) {
        case 'html':
        case 'htm':
            $html .= '<div>' . $content . '</div>';
            break;
        case 'txt':
            $html .= '<pre>' . htmlspecialchars($content) . '</pre>';
            break;
        default:
            $html .= '<h1>Unsupported File Format</h1>';
            $html .= '<p>The file format "' . htmlspecialchars($extension) . '" is not currently supported for PDF conversion.</p>';
            break;
    }

    $html .= '</body></html>';
    return $html;
}