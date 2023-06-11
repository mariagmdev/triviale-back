<?php

namespace Controllers;

use Helpers\RespuestaHelper;
use Services\BaseDatosService;
use Services\SesionService;

/**
 * Clase controladora que gestiona todo lo relacionado con la entidad de Puntuación.
 */
class PuntuacionesController
{
    /**
     * Crear una nueva puntuación.
     *
     * @param array $preguntas preguntas a evaluar.
     * @param integer $tiempo tiempo de la partida.
     * @return void
     */
    function crear(array $preguntas, int $tiempo): void
    {
        $sesion = new SesionService();
        // Comprobamos que el usuario tiene la sesión abierta.
        if ($sesion->haySesion()) {
            // Nos quedamos con los ids de las preguntas.
            $idPreguntas = [];
            foreach ($preguntas as $pregunta) {
                $idPreguntas[count($idPreguntas)] = $pregunta->idPregunta;
            }

            // Obtener la respuesta correcta para cada id de pregunta.
            $idPreguntasQuery = implode(',', $idPreguntas);
            $bd = new BaseDatosService();
            $query = "SELECT id, idPregunta FROM respuestas WHERE esCorrecta=1 AND idPregunta IN ($idPreguntasQuery)";
            $resultado = $bd->consultar($query);
            $puntos = 0;

            // Comprobar la respuesta repondida es la correcta para cada pregunta
            // si es correcta sumamos puntos.
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

            // Insertamos la puntuación generada.
            $sentence = "INSERT into puntuaciones (idUsuario,puntos,tiempo) values({$sesion->getId()},$puntos, $tiempo)";
            $bd->ejecutar($sentence);
            RespuestaHelper::enviarRespuesta(200, $puntos);
        } else {
            RespuestaHelper::enviarRespuesta(403);
        }
    }

    /**
     * Obtener ranking de las mejores 10 puntuaciones.
     *
     * @return void
     */
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
            $usuarioPuntuaciones[count($usuarioPuntuaciones)] = $usuarioPuntuacion;
        }
        RespuestaHelper::enviarRespuesta(200, $usuarioPuntuaciones);
    }
}
