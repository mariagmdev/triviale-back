<?php

namespace Controllers;

use Helpers\RespuestaHelper;
use Models\Error\Error;
use Models\Pregunta\Pregunta;
use Models\Respuesta\Respuesta;
use Services\BaseDatosService;

class PreguntasController
{

    function obtenerXPreguntasAleatoriasPorCategoria(int $idCategoria, int $cantidad = 10)
    {
        $bd = new BaseDatosService();
        $consultaPreguntas = "SELECT p.id, p.titulo FROM preguntas p WHERE idCategoria=$idCategoria AND p.esPublica=1";
        $resultadoPreguntas = $bd->consultar($consultaPreguntas);
        $consultaRespuestas = "SELECT r.id, r.titulo, r.idPregunta FROM respuestas r JOIN preguntas p ON p.id=r.idPregunta WHERE p.idCategoria=$idCategoria AND p.esPublica=1";
        $resultadoRespuestas = $bd->consultar($consultaRespuestas);
        shuffle($resultadoPreguntas);
        $resultadoPreguntas = array_slice($resultadoPreguntas, 0, $cantidad);
        $preguntas = [];
        foreach ($resultadoPreguntas as $pregunta) {
            $respuestas = [];
            foreach ($resultadoRespuestas as $respuesta) {
                if ($respuesta['idPregunta'] === $pregunta['id']) {
                    array_push($respuestas, new Respuesta($respuesta));
                }
            };
            shuffle($respuestas);
            array_push($preguntas, new Pregunta(array_merge($pregunta, ['respuestas' => $respuestas])));
        }

        RespuestaHelper::enviarRespuesta(200, $preguntas);
    }

    function validar(int $idPregunta, int $idRespuesta): void
    {
        $bd = new BaseDatosService();
        $consulta = "SELECT r.esCorrecta FROM respuestas r WHERE r.id=$idRespuesta AND r.idPregunta=$idPregunta";
        $resultado = $bd->consultar($consulta);
        if (empty($resultado)) {
            RespuestaHelper::enviarRespuesta(400, new Error("La pregunta o la respuesta no existen."));
        }
        RespuestaHelper::enviarRespuesta(200, (int)$resultado[0]['esCorrecta'] === 1);
    }
}
