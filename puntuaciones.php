<?php
spl_autoload_register();

use Controllers\PuntuacionesController;
use Helpers\PeticionHelper;
use Helpers\RespuestaHelper;
use Models\Pregunta\PreguntaRespondida;
use Services\InicioService;

// Obtener el cuerpo de la petición.
$body = PeticionHelper::getBody();

// Instanciar recursos necesarios.
$controlador = new PuntuacionesController();
new InicioService();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($body['preguntas']) && isset($body['crear'])) {
    // Crear objetos de los modelos a partir del cuerpo de la petición.
    $preguntas = [];
    foreach ($body['preguntas'] as $pregunta) {
        $preguntas[count($preguntas)] = new PreguntaRespondida($pregunta);
    }

    $controlador->crear($preguntas, (int)$body['tiempo']);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['ranking']) {
    $controlador->obtenerRanking();
}

// Sino existen rutas, devolver 404 Not Found.
RespuestaHelper::enviarRespuesta(404);
