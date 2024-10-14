<?php
spl_autoload_register(function ($class_name) {
    $directories = [
        __DIR__ . '/../database/',
        __DIR__ . '/../utils/'
    ];

    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.class.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
?>
