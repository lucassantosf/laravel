<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    $prefix = 'Desafio08\\';
    $baseDir = __DIR__ . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

function print_m($text){
    echo PHP_EOL . "{$text}" . PHP_EOL . PHP_EOL;
}


print_m(">>> Script finalizado com sucesso! <<<");
