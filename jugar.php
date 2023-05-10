<?php
spl_autoload_register();

use Controllers\AuthController;
use Controllers\PreguntasController;
use Helpers\PeticionHelper;
use Helpers\RespuestaHelper;
use Services\InicioService;

$controlador = new PreguntasController();
new InicioService();

$controlador->obtenerXPreguntasAleatoriasPorCategorias();

RespuestaHelper::enviarRespuesta(404);