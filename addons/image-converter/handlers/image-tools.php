<?php
use App\Models\UsageLog;
use Illuminate\Support\Facades\Auth;

// Handle Image Tools processing
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$tool = $_POST['tool'] ?? 'convert';
$user = Auth::user();
$isGuest = !$user;

// For now, allow all tools for guests to test functionality
// TODO: Implement proper guest restrictions later

// Process based on tool
switch ($tool) {
    case 'convert':
        processImageConvert($user, $isGuest);
        break;
    case 'compress':
        processImageCompress($user, $isGuest);
        break;
    case 'resize':
        processImageResize($user, $isGuest);
        break;
    case 'crop':
        processImageCrop($user, $isGuest);
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown tool']);
        exit;
}

// Tool implementations

function processImageConvert($user, $isGuest) {
    if (!isset($_FILES['files']) || empty($_FILES['files'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No files uploaded']);
        exit;
    }

    $files = $_FILES['files'];
    $targetFormat = $_POST['target_format'] ?? 'jpg';
    $compression = (int)($_POST['compression'] ?? 85);

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

    if (empty($uploadedFiles)) {
        http_response_code(400);
        echo json_encode(['error' => 'No valid files uploaded']);
        exit;
    }

    try {
        $processedFiles = [];

        foreach ($uploadedFiles as $file) {
            // For now, implement basic conversion simulation
            // In a real implementation, you'd use ImageMagick, GD, or similar libraries

            // Simulate processing by copying the file (placeholder)
            $outputFilename = pathinfo($file['name'], PATHINFO_FILENAME) . '.' . $targetFormat;
            $processedFiles[] = $outputFilename;

            // Log conversion
            logImageConversion($user, $isGuest, 'image-converter', 'convert', $file['name'], $outputFilename, $file['size']);
        }

        // For multiple files, create a ZIP
        if (count($uploadedFiles) > 1) {
            $zipFilename = 'converted_images.zip';

            // Create ZIP file (placeholder - in real implementation)
            $zipContent = 'Simulated ZIP content'; // Replace with actual ZIP creation

            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipFilename . '"');
            header('Content-Length: ' . strlen($zipContent));
            echo $zipContent;
        } else {
            // Single file - return the processed file
            $file = $uploadedFiles[0];
            $outputFilename = pathinfo($file['name'], PATHINFO_FILENAME) . '.' . $targetFormat;

            // For now, return the original file as placeholder
            $fileContent = file_get_contents($file['tmp_name']);

            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'webp' => 'image/webp',
                'gif' => 'image/gif',
                'pdf' => 'application/pdf',
                'svg' => 'image/svg+xml',
                'tiff' => 'image/tiff',
                'bmp' => 'image/bmp'
            ];

            $mimeType = $mimeTypes[$targetFormat] ?? 'application/octet-stream';

            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: attachment; filename="' . $outputFilename . '"');
            header('Content-Length: ' . strlen($fileContent));
            echo $fileContent;
        }

        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to convert images: ' . $e->getMessage()]);
        exit;
    }
}

function processImageCompress($user, $isGuest) {
    // Placeholder implementation
    http_response_code(501);
    echo json_encode(['error' => 'Image compression not yet implemented']);
    exit;
}

function processImageResize($user, $isGuest) {
    // Placeholder implementation
    http_response_code(501);
    echo json_encode(['error' => 'Image resize not yet implemented']);
    exit;
}

function processImageCrop($user, $isGuest) {
    // Placeholder implementation
    http_response_code(501);
    echo json_encode(['error' => 'Image crop not yet implemented']);
    exit;
}

// Helper functions
function logImageConversion($user, $isGuest, $addonSlug, $action, $inputFilename, $outputFilename, $fileSize) {
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