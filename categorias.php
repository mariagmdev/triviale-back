<?php
spl_autoload_register();

use Controllers\CategoriasController;
use Helpers\PeticionHelper;
use Helpers\RespuestaHelper;
use Services\InicioService;

$body = PeticionHelper::getBody();
$controlador = new CategoriasController();
new InicioService();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controlador->listar();
}

RespuestaHelper::enviarRespuesta(404);
