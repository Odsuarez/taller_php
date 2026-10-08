<?php

// Carga automática de clases: al usar "new Acronimo()" PHP la busca solo
// dentro de estas subcarpetas, así no hace falta un require por cada clase.
spl_autoload_register(function (string $clase): void {
    $carpetas = ['Utilidades', 'Web', 'Logica'];

    foreach ($carpetas as $carpeta) {
        $ruta = __DIR__ . '/' . $carpeta . '/' . $clase . '.php';

        if (is_file($ruta)) {
            require_once $ruta;
            return;
        }
    }
});
