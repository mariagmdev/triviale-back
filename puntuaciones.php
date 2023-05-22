<?php
spl_autoload_register();

use Controllers\PuntuacionesController;
use Helpers\PeticionHelper;
use Helpers\RespuestaHelper;
use Models\Pregunta\PreguntaRespondida;
use Services\InicioService;

$body = PeticionHelper::getBody();
$controlador = new PuntuacionesController();
new InicioService();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($body['preguntas']) && isset($body['crear'])) {
    $preguntas = [];
    foreach ($body['preguntas'] as $pregunta) {
        array_push($preguntas, new PreguntaRespondida($pregunta));
    }

    $controlador->crear($preguntas);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['ranking']) {
    $controlador->obtenerRanking();
}

RespuestaHelper::enviarRespuesta(404);
