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

// Obtener el cuerpo de la petición.
$body = PeticionHelper::getBody();

// Instanciar recursos necesarios.
$controlador = new PreguntasController();
new InicioService();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($body['idCategorias']) && isset($body['preguntasPartida'])) {
    $controlador->obtenerXPreguntasAleatoriasPorCategorias($body['idCategorias']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($body['idPregunta']) && isset($body['idRespuesta']) && isset($body['validar'])) {
    $controlador->validar($body['idPregunta'], $body['idRespuesta']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($body['crear'])) {
    // Crear objetos según el modelo de RespuestaCreacion y PreguntaCreacion para poder utilizarlos
    // más facilmente en el controlador.
    $respuestas = [];
    foreach ($body['respuestas'] as $respuesta) {
        $respuestas[count($respuestas)] = new RespuestaCreacion($respuesta);
    }
    $bodyPregunta = [
        'titulo' => $body['titulo'],
        'idCategoria' => $body['idCategoria'],
        'categoria' => $body['categoria'],
        'respuestas' => $respuestas,
    ];

    if (isset($body['imgCategoria'])) {
        $bodyPregunta[count($bodyPregunta)] = ['imgCategoria' => $body['imgCategoria']];
    }
    $pregunta = new PreguntaCreacion($bodyPregunta);
    $controlador->crear($pregunta);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET)) {
    $controlador->listar();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['id']) {
    $controlador->obtener($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT' && $_GET['id'] && isset($body['modificar'])) {
    // Crear objetos de los modelos a partir del cuerpo de la petición.
    $respuestas = [];
    foreach ($body['respuestas'] as $respuesta) {
        $respuestas[count($respuestas)] = new RespuestaEdicion($respuesta);
    }
    $bodyPregunta = [
        'id' => $body['id'],
        'titulo' => $body['titulo'],
        'idCategoria' => $body['idCategoria'],
        'esPublica' => $body['esPublica'],
        'respuestas' => $respuestas,
    ];
    if (isset($body['imgCategoria'])) {
        $bodyPregunta['imgCategoria'] = $body['imgCategoria'];
    }
    if (isset($body['nombreCategoria'])) {
        $bodyPregunta['nombreCategoria'] = $body['nombreCategoria'];
    }
    $pregunta = new PreguntaEdicion($bodyPregunta);
    $controlador->modificar($_GET['id'], $pregunta);
}

if ($_SERVER['REQUEST_METHOD'] === 'PATCH' && isset($body['visibilidad'])) {
    $controlador->establecerVisibilidad($body['esPublica'], $body['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($body['idCategorias']) && isset($body['exportar'])) {
    $controlador->exportar($body['idCategorias'], $body['tipo']);
}

// Sino existen rutas, devolver 404 Not Found.
RespuestaHelper::enviarRespuesta(404);
