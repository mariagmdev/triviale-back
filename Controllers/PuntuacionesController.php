<?php

namespace Controllers;

use Helpers\RespuestaHelper;
use Services\BaseDatosService;
use Services\SesionService;

class PuntuacionesController
{

    function crear(array $preguntas, int $tiempo): void
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
            $sentence = "INSERT into puntuaciones (idUsuario,puntos,tiempo) values({$sesion->getId()},$puntos, $tiempo)";
            $bd->ejecutar($sentence);
            RespuestaHelper::enviarRespuesta(200, $puntos);
        } else {
            RespuestaHelper::enviarRespuesta(403);
        }
    }

    function obtenerRanking(): void
    {
        $bd = new BaseDatosService();
        $query = "SELECT p.puntos, p.tiempo, u.nombre FROM puntuaciones p JOIN usuarios u ON p.idUsuario=u.id 
            WHERE (p.idUsuario,p.puntos) 
            IN (SELECT pp.idUsuario, MAX(pp.puntos) puntos FROM puntuaciones pp GROUP BY pp.idUsuario ORDER BY puntos DESC)
            GROUP BY p.idUsuario, p.puntos
            ORDER BY p.puntos DESC LIMIT 10;";
        $resultado = $bd->consultar($query);
        $usuarioPuntuaciones = [];
        foreach ($resultado as $usuarioPuntuacion) {
            array_push($usuarioPuntuaciones, $usuarioPuntuacion);
        }
        RespuestaHelper::enviarRespuesta(200, $usuarioPuntuaciones);
    }
}
