<?php
spl_autoload_register();

use Controllers\AuthController;
use Helpers\PeticionHelper;
use Helpers\RespuestaHelper;
use Models\Usuario\UsuarioInicio;
use Models\Usuario\UsuarioRegistro;
use Services\InicioService;

$body = PeticionHelper::getBody();
$controlador = new AuthController();
new InicioService();

if (isset($body['nombre']) && isset($body['clave']) && isset($body['clave2'])) {
    $controlador->registrar(new UsuarioRegistro($body));
}

if (isset($body['nombre']) && isset($body['clave'])) {
    $controlador->iniciarSesion(new UsuarioInicio($body));
}

RespuestaHelper::enviarRespuesta(404);