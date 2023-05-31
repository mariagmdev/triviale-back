<?php
spl_autoload_register();

use Controllers\PreguntasController;
use Helpers\PeticionHelper;
use Helpers\RespuestaHelper;
use Models\Pregunta\PreguntaCreacion;
use Models\Pregunta\PreguntaEdicion;
use Models\Respuesta\RespuestaCreacion;
use Models\Respuesta\RespuestaEdicion;
use Services\InicioService;

$body = PeticionHelper::getBody();
$controlador = new PreguntasController();
new InicioService();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($body['idPregunta']) && isset($body['idRespuesta']) && isset($body['validar'])) {
    $controlador->validar($body['idPregunta'], $body['idRespuesta']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($body['crear'])) {
    $respuestas = [];
    foreach ($body['respuestas'] as $respuesta) {
        array_push($respuestas, new RespuestaCreacion($respuesta));
    }
    $pregunta = new PreguntaCreacion([
        'titulo' => $body['titulo'],
        'idCategoria' => $body['idCategoria'],
        'categoria' => $body['categoria'],
        'respuestas' => $respuestas,

    ]);
    $controlador->crear($pregunta);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET)) {
    $controlador->listar();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['id']) {
    $controlador->obtener($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT' && $_GET['id'] && isset($body['modificar'])) {
    $respuestas = [];
    foreach ($body['respuestas'] as $respuesta) {
        array_push($respuestas, new RespuestaEdicion($respuesta));
    }
    $pregunta = new PreguntaEdicion([
        'id' => $body['id'],
        'titulo' => $body['titulo'],
        'idCategoria' => $body['idCategoria'],
        'nombreCategoria' => $body['nombreCategoria'],
        'respuestas' => $respuestas,
    ]);
    $controlador->modificar($_GET['id'], $pregunta);
}

if ($_SERVER['REQUEST_METHOD'] === 'PATCH' && isset($body['visibilidad'])) {
    $controlador->establecerVisibilidad($body['esPublica'], $body['id']);
}

RespuestaHelper::enviarRespuesta(404);
