<?php
spl_autoload_register();

use Controllers\AuthController;
use Helpers\PeticionHelper;
use Helpers\RespuestaHelper;
use Models\Usuario\UsuarioInicio;
use Models\Usuario\UsuarioRegistro;
use Services\InicioService;

// Obtener el cuerpo de la petición.
$body = PeticionHelper::getBody();

// Instanciar recursos necesarios.
$controlador = new AuthController();
new InicioService();

if (isset($body['nombre']) && isset($body['clave']) && isset($body['clave2'])) {
    $controlador->registrar(new UsuarioRegistro($body));
}

if (isset($body['nombre']) && isset($body['clave'])) {
    $controlador->iniciarSesion(new UsuarioInicio($body));
}

// Sino existen rutas, devolver 404 Not Found.
RespuestaHelper::enviarRespuesta(404);
