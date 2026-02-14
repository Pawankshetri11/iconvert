<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$ref = new ReflectionClass($app);
echo "AppFile: " . $ref->getFileName() . "\n";
echo "AliasFiles: " . $app->getAlias('files') . "\n";
try {
   $app->make('files');
   echo "OK\n";
} catch(Throwable $e) {
   echo "Error: " . $e->getMessage() . "\n";
}
