<?php
spl_autoload_register();

use Controllers\PreguntasController;
use Helpers\PeticionHelper;
use Helpers\RespuestaHelper;
use Services\InicioService;

$body = PeticionHelper::getBody();
$controlador = new PreguntasController();
new InicioService();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($body['idPregunta']) && isset($body['idRespuesta']) && isset($body['validar'])) {
    $controlador->validar($body['idPregunta'], $body['idRespuesta']);
}

RespuestaHelper::enviarRespuesta(404);
