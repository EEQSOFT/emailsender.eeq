<?php

spl_autoload_register(function ($class) {
    $directory = __DIR__ . '/';
    $prefix = 'App\\';
    $length = strlen($prefix);

    if (strncmp($class, $prefix, $length) !== 0) {
        return;
    }

    $relativeClass = substr($class, $length);
    $file = $directory . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once($file);
    }

    if (method_exists($class, 'init')) {
        $class::init();
    }
});
