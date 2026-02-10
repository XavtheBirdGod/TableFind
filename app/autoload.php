<?php
declare(strict_types=1);

/**
 * PSR-4 Autoloader implementatie.
 */
spl_autoload_register(function ($class) {
    // Project-specifieke namespace prefix
    $prefix = 'App\\';

    // Basis directory voor de App namespace
    $base_dir = __DIR__ . '/';

    // Heeft de class de prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Verkrijg de relatieve class naam
    $relative_class = substr($class, $len);

    // Vervang namespace separators door directory separators en voeg .php toe
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Als het bestand bestaat, laad het
    if (file_exists($file)) {
        require $file;
    }
});