<?php
require __DIR__.'/vendor/autoload.php';

echo "Autoload loaded.\n";

if (class_exists(Illuminate\Filesystem\Filesystem::class)) {
    echo "Filesystem class exists.\n";
} else {
    echo "Filesystem class MISSING.\n";
}

$app = require __DIR__.'/bootstrap/app.php';
echo "App created.\n";
echo "App Class: " . get_class($app) . "\n";
$ref = new ReflectionClass($app);
echo "App File: " . $ref->getFileName() . "\n";

echo "Alias 'files' points to: " . $app->getAlias('files') . "\n";

echo "Bound 'files'? " . ($app->bound('files') ? 'Yes' : 'No') . "\n";

try {
    $fs = $app->make('files');
    echo "Files resolved: " . get_class($fs) . "\n";
} catch (Throwable $e) {
    echo "Error resolving files: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

