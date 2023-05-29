<?php
spl_autoload_register();

use Controllers\PreguntasController;
use Helpers\PeticionHelper;
use Helpers\RespuestaHelper;
use Models\Pregunta\PreguntaCreacion;
use Models\Respuesta\RespuestaCreacion;
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

RespuestaHelper::enviarRespuesta(404);
