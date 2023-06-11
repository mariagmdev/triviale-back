<?php
spl_autoload_register();

use Controllers\CategoriasController;
use Helpers\PeticionHelper;
use Helpers\RespuestaHelper;
use Services\InicioService;

// Obtener el cuerpo de la petición.
$body = PeticionHelper::getBody();

// Instanciar recursos necesarios.
$controlador = new CategoriasController();
new InicioService();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['partida'])) {
        $controlador->listarCategoriasPartida();
    } else {
        $controlador->listar();
    }
}

// Sino existen rutas, devolver 404 Not Found.
RespuestaHelper::enviarRespuesta(404);
