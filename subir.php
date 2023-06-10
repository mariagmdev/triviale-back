<?php
spl_autoload_register();

use Controllers\AuthController;
use Controllers\ImagenController;
use Helpers\PeticionHelper;
use Helpers\RespuestaHelper;
use Services\InicioService;

$controlador = new ImagenController();
new InicioService();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES) && isset($_FILES['imgCategoria'])) {
    $controlador->subir($_FILES['imgCategoria']);
}
RespuestaHelper::enviarRespuesta(404);