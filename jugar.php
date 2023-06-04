<?php
spl_autoload_register();

use Controllers\AuthController;
use Controllers\PreguntasController;
use Helpers\PeticionHelper;
use Helpers\RespuestaHelper;
use Services\InicioService;

$controlador = new PreguntasController();
$body = PeticionHelper::getBody();
new InicioService();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($body['idCategorias'])) {
    $controlador->obtenerXPreguntasAleatoriasPorCategoria($body['idCategorias']);
}

RespuestaHelper::enviarRespuesta(404);
