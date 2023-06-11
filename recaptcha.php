<?php
spl_autoload_register();

use Controllers\RecaptchaController;
use Helpers\PeticionHelper;
use Helpers\RespuestaHelper;
use Services\InicioService;

// Obtener el cuerpo de la petición.
$body = PeticionHelper::getBody();

// Instanciar recursos necesarios.
$controlador = new RecaptchaController();
new InicioService();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($body['verificar'])) {
    $controlador->verificar($body['token']);
}

// Sino existen rutas, devolver 404 Not Found.
RespuestaHelper::enviarRespuesta(404);
