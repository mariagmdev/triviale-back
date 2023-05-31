<?php

namespace Controllers;

use Enums\Rol;
use Helpers\RespuestaHelper;
use Models\Error\Error;
use Models\Pregunta\Pregunta;
use Models\Pregunta\PreguntaCreacion;
use Models\Pregunta\PreguntaEdicion;
use Models\Pregunta\PreguntaGeneral;
use Models\Respuesta\Respuesta;
use Models\Respuesta\RespuestaEdicion;
use Services\BaseDatosService;
use Services\SesionService;

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

    function crear(PreguntaCreacion $pregunta): void
    {
        $bd = new BaseDatosService();
        if ($pregunta->idCategoria === 0) {
            $sentenciaCategoria = "INSERT into categorias (nombre) VALUES ('$pregunta->categoria')";
            $bd->ejecutar($sentenciaCategoria);
            $pregunta->idCategoria = (int) $bd->obtenerUltimoId();
        }
        $sentenciaPregunta = "INSERT into preguntas (titulo, idCategoria) VALUES ('$pregunta->titulo',$pregunta->idCategoria)";
        $bd->ejecutar($sentenciaPregunta);
        $idPregunta = (int) $bd->obtenerUltimoId();
        $sentenciaRespuestas = "INSERT into respuestas (titulo, idPregunta, esCorrecta) VALUES ";
        $valoresRespuesta = [];
        foreach ($pregunta->respuestas as $respuesta) {
            array_push($valoresRespuesta, "('$respuesta->titulo', $idPregunta," . ((int) $respuesta->esCorrecta) . ")");
        }
        $sentenciaRespuestas .= implode(",", $valoresRespuesta);
        $bd->ejecutar($sentenciaRespuestas);

        RespuestaHelper::enviarRespuesta(204);
    }

    function listar(): void
    {
        $sesion = new SesionService();
        if (Rol::Administrador->value === $sesion->getIdRol()) {
            $bd = new BaseDatosService();
            $consulta = "SELECT p.id, p.titulo, p.esPublica, c.id idCategoria, c.nombre nombreCategoria FROM preguntas p JOIN categorias c ON p.idCategoria=c.id";
            $resultado = $bd->consultar($consulta);
            $preguntas = [];
            foreach ($resultado as $pregunta) {
                array_push($preguntas, new PreguntaGeneral($pregunta));
            }
            RespuestaHelper::enviarRespuesta(200, $preguntas);
        } else {
            RespuestaHelper::enviarRespuesta(401);
        }
    }

    function obtener(int $id): void
    {
        $sesion = new SesionService();
        if (Rol::Administrador->value === $sesion->getIdRol()) {
            $bd = new BaseDatosService();
            $consultaPregunta = "SELECT p.id, p.titulo, p.esPublica, c.id idCategoria, c.nombre nombreCategoria FROM preguntas p JOIN categorias c ON p.idCategoria=c.id WHERE p.id=$id";
            $resultadoPregunta = $bd->consultar($consultaPregunta);
            $consultaRespuestas = "SELECT * FROM respuestas WHERE idPregunta=$id";
            $resultadoRespuestas = $bd->consultar($consultaRespuestas);
            $respuestas = [];
            foreach ($resultadoRespuestas as $respuesta) {
                array_push($respuestas, new RespuestaEdicion($respuesta));
            }
            $pregunta = new PreguntaEdicion(array_merge($resultadoPregunta[0], ['respuestas' => $respuestas]));
            RespuestaHelper::enviarRespuesta(200, $pregunta);
        } else {
            RespuestaHelper::enviarRespuesta(401);
        }
    }

    function modificar(int $id, PreguntaEdicion $pregunta): void
    {
        $sesion = new SesionService();
        if (Rol::Administrador->value === $sesion->getIdRol()) {
            if ($id === $pregunta->id) {
                $bd = new BaseDatosService();
                if ($pregunta->idCategoria === 0) {
                    $sentenciaCategoria = "INSERT into categorias (nombre) VALUES ('$pregunta->nombreCategoria')";
                    $bd->ejecutar($sentenciaCategoria);
                    $pregunta->idCategoria = (int) $bd->obtenerUltimoId();
                }
                $sentenciaPregunta = "UPDATE preguntas SET 
                    titulo='$pregunta->titulo',
                    esPublica=" . ($pregunta->esPublica ? 1 : 0) . ",
                    idCategoria=$pregunta->idCategoria
                    WHERE id=$id";
                $bd->ejecutar($sentenciaPregunta);
                foreach ($pregunta->respuestas as $respuesta) {
                    $sentenciaRespuesta = "UPDATE respuestas SET titulo='$respuesta->titulo', esCorrecta=" . ($respuesta->esCorrecta ? 1 : 0) . " WHERE id=$respuesta->id";
                    $bd->ejecutar($sentenciaRespuesta);
                }

                RespuestaHelper::enviarRespuesta(204);
            } else {
                RespuestaHelper::enviarRespuesta(400, ['error' => 'Los id no coinciden.']);
            }
        } else {
            RespuestaHelper::enviarRespuesta(401);
        }
    }

    function establecerVisibilidad(bool $esPublica, int $id): void
    {
        $sesion = new SesionService();
        if (Rol::Administrador->value === $sesion->getIdRol()) {
            $bd = new BaseDatosService();
            $sentencia = "UPDATE preguntas SET esPublica=" . ($esPublica ? 1 : 0) . " WHERE id=$id";
            $bd->ejecutar($sentencia);
            RespuestaHelper::enviarRespuesta(204);
        } else {
            RespuestaHelper::enviarRespuesta(401);
        }
    }
}
