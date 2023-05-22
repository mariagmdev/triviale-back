<?php

namespace Controllers;

use Helpers\RespuestaHelper;
use Services\BaseDatosService;
use Services\SesionService;

class PuntuacionesController
{

    function crear(array $preguntas): void
    {
        $sesion = new SesionService();
        if ($sesion->haySesion()) {
            $idPreguntas = [];
            foreach ($preguntas as $pregunta) {
                array_push($idPreguntas, $pregunta->idPregunta);
            }
            $idPreguntasQuery = implode(',', $idPreguntas);
            $bd = new BaseDatosService();
            $query = "SELECT id, idPregunta FROM respuestas WHERE esCorrecta=1 AND idPregunta IN ($idPreguntasQuery)";
            $resultado = $bd->consultar($query);
            $puntos = 0;

            foreach ($preguntas as $pregunta) {
                $respuestaCorrecta = null;
                $i = 0;
                while ($respuestaCorrecta === null) {
                    if ((int)$resultado[$i]['idPregunta'] === $pregunta->idPregunta) {
                        $respuestaCorrecta = $resultado[$i];
                    }
                    $i++;
                }
                if ((int)$respuestaCorrecta['id'] === $pregunta->idRespuesta) {
                    $puntos += 10;
                }
            }
            $sentence = "INSERT into puntuaciones (idUsuario,puntos) values({$sesion->getId()},$puntos)";
            $bd->ejecutar($sentence);
            RespuestaHelper::enviarRespuesta(200, $puntos);
        } else {
            RespuestaHelper::enviarRespuesta(403);
        }
    }

    function obtenerRanking(): void
    {
        $bd = new BaseDatosService();
        $query = "SELECT MAX(p.puntos) puntos, u.nombre FROM puntuaciones p JOIN usuarios u ON p.idUsuario=u.id GROUP BY p.idUsuario ORDER BY puntos DESC LIMIT 10";
        $resultado = $bd->consultar($query);
        $usuarioPuntuaciones = [];
        foreach ($resultado as $usuarioPuntuacion) {
            array_push($usuarioPuntuaciones, $usuarioPuntuacion);
        }
        RespuestaHelper::enviarRespuesta(200, $usuarioPuntuaciones);
    }
}
