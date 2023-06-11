<?php
spl_autoload_register();

use Controllers\ImagenController;
use Helpers\RespuestaHelper;
use Services\InicioService;

// Instanciar recursos necesarios.
$controlador = new ImagenController();
new InicioService();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES) && isset($_FILES['imgCategoria'])) {
    $controlador->subir($_FILES['imgCategoria']);
}

// Sino existen rutas, devolver 404 Not Found.
RespuestaHelper::enviarRespuesta(404);
