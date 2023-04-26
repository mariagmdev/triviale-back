<?php
spl_autoload_register();

use Controllers\AuthController;
use Helpers\PeticionHelper;
use Models\Usuario\UsuarioRegistro;
use Services\InicioService;

$body = PeticionHelper::getBody();
$controlador = new AuthController();
new InicioService();

if (isset($body['nombre']) && isset($body['clave']) && isset($body['clave2'])) {
    $controlador->registrar(new UsuarioRegistro($body));
    exit;
}