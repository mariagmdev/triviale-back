<?php
spl_autoload_register();

use Controllers\RecaptchaController;
use Helpers\PeticionHelper;
use Helpers\RespuestaHelper;
use Services\InicioService;

$body = PeticionHelper::getBody();
$controlador = new RecaptchaController();
new InicioService();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($body['verificar'])) {
    $controlador->verificar($body['token']);
}

RespuestaHelper::enviarRespuesta(404);
