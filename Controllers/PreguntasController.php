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
use SimpleXMLElement;

/**
 * Clase controladora que gestiona todo lo relacionado con la entidad de Preguntas.
 */
class PreguntasController
{
    /**
     * Obtiene preguntas alteaorias dadas unas categorías.
     *
     * @param array $idCategorias id de las categorías
     * @param integer $cantidad cantidad de preguntas a obtener
     * @return void
     */
    function obtenerXPreguntasAleatoriasPorCategorias(array $idCategorias, int $cantidad = 15): void
    {
        $sesion = new SesionService();
        // Comprobamos que el usuario tiene la sesión abierta.
        if ($sesion->haySesion()) {
            $bd = new BaseDatosService();
            // Obtenemos todas las preguntas de las categorías dadas.
            $consultaPreguntas = "SELECT p.id, p.titulo, c.nombre nombreCategoria, c.img imgCategoria, c.id idCategoria FROM preguntas p LEFT JOIN categorias c ON c.id=p.idCategoria WHERE c.id in (" . implode(',', $idCategorias) . ") AND p.esPublica=1";
            $resultadoPreguntas = $bd->consultar($consultaPreguntas);

            // Obtenemos las respuestas de las preguntas de las categorías dadas.
            $consultaRespuestas = "SELECT r.id, r.titulo, r.idPregunta FROM respuestas r JOIN preguntas p ON p.id=r.idPregunta WHERE p.idCategoria in (" . implode(',', $idCategorias) . ") AND p.esPublica=1";
            $resultadoRespuestas = $bd->consultar($consultaRespuestas);

            // Desordenamos las preguntas.
            shuffle($resultadoPreguntas);

            // Cortamos la cantidad necesaria de preguntas.
            $resultadoPreguntas = array_slice($resultadoPreguntas, 0, $cantidad);

            // Creamos objetos en base a los modelos a partir de los datos obtenidos.
            $preguntas = [];
            foreach ($resultadoPreguntas as $pregunta) {
                // Buscamos y adjuntamos las respuestas de cada pregunta.
                $respuestas = [];
                foreach ($resultadoRespuestas as $respuesta) {
                    if ($respuesta['idPregunta'] === $pregunta['id']) {
                        $respuestas[count($respuestas)] = new Respuesta($respuesta);
                    }
                };

                // Desordenamos las respuestas.
                shuffle($respuestas);

                // Añadirmos la pregunta completa al listado de preguntas a devolver.
                $preguntas[count($preguntas)] = new Pregunta(array_merge($pregunta, ['respuestas' => $respuestas]));
            }
            RespuestaHelper::enviarRespuesta(200, $preguntas);
        } else {
            RespuestaHelper::enviarRespuesta(401);
        }
    }

    /**
     * Comprueba si la respuesta dada es la respuesta correcta para la pregunta.
     *
     * @param integer $idPregunta id de pregunta a comprobar.
     * @param integer $idRespuesta id de respuesta a comprobar.
     * @return void
     */
    function validar(int $idPregunta, int $idRespuesta): void
    {
        $sesion = new SesionService();
        // Comprobamos que el usuario tiene la sesión abierta.
        if ($sesion->haySesion()) {
            // Obtenemos cuál es la respuesta correcta para la pregunta.
            $bd = new BaseDatosService();
            $consulta = "SELECT r.esCorrecta FROM respuestas r WHERE r.id=$idRespuesta AND r.idPregunta=$idPregunta";
            $resultado = $bd->consultar($consulta);

            // Sino obtenemos resultados, devolver error 400 Bad Request.
            if (empty($resultado)) {
                RespuestaHelper::enviarRespuesta(400, new Error("La pregunta o la respuesta no existen."));
            } else {
                RespuestaHelper::enviarRespuesta(200, (int)$resultado[0]['esCorrecta'] === 1);
            }
        } else {
            RespuestaHelper::enviarRespuesta(401);
        }
    }

    /**
     * Crea una pregunta.
     *
     * @param PreguntaCreacion $pregunta pregunta a crear
     * @return void
     */
    function crear(PreguntaCreacion $pregunta): void
    {
        $sesion = new SesionService();
        // Comprobamos que el usuario tiene la sesión abierta.
        if ($sesion->haySesion()) {
            $bd = new BaseDatosService();

            // Insertamos una categoría nueva si se ha utilizado una nueva categoría.
            if ($pregunta->idCategoria === 0) {
                $columnasAInsertar = ['nombre'];
                $valoresAInsertar = ["'$pregunta->categoria'"];
                if (isset($pregunta->imgCategoria)) {
                    $columnasAInsertar[count($columnasAInsertar)] = 'img';
                    $valoresAInsertar[count($valoresAInsertar)] = "'$pregunta->imgCategoria'";
                }
                $sentenciaCategoria = "INSERT into categorias (" . implode(',', $columnasAInsertar) . ") VALUES (" . implode(',', $valoresAInsertar) . ")";
                $bd->ejecutar($sentenciaCategoria);

                // Guardamos en el mismo objeto el id de la categoría insertada.
                $pregunta->idCategoria = (int) $bd->obtenerUltimoId();
            }

            // Insertamos la nueva pregunta.
            $sentenciaPregunta = "INSERT into preguntas (titulo, idCategoria) VALUES ('" . addslashes($pregunta->titulo) . "',$pregunta->idCategoria)";
            $bd->ejecutar($sentenciaPregunta);

            // Obtenemos el id de la pregunta insertada.
            $idPregunta = (int) $bd->obtenerUltimoId();

            // Generamos la sentencia para insertar todas las respuestas asociadas a esta pregunta.
            $sentenciaRespuestas = "INSERT into respuestas (titulo, idPregunta, esCorrecta) VALUES ";
            $valoresRespuesta = [];
            foreach ($pregunta->respuestas as $respuesta) {
                $valoresRespuesta[count($valoresRespuesta)] = "('" . addslashes($respuesta->titulo) . "', $idPregunta," . ((int) $respuesta->esCorrecta) . ")";
            }
            $sentenciaRespuestas .= implode(",", $valoresRespuesta);
            $bd->ejecutar($sentenciaRespuestas);

            // Devolvemos que todo ha ido bien pero no tenemos contenido que devolver 204 No content.
            RespuestaHelper::enviarRespuesta(204);
        } else {
            RespuestaHelper::enviarRespuesta(401);
        }
    }

    /**
     * Lista las preguntas
     *
     * @return void
     */
    function listar(): void
    {
        $sesion = new SesionService();
        // Comprobamos que el usuario tiene la sesión abierta y que su rol es admin.
        if (Rol::Administrador->value === $sesion->getIdRol()) {
            // Obtenemos todas las preguntas.
            $bd = new BaseDatosService();
            $consulta = "SELECT p.id, p.titulo, p.esPublica, c.id idCategoria, c.nombre nombreCategoria FROM preguntas p JOIN categorias c ON p.idCategoria=c.id ORDER BY p.esPublica";
            $resultado = $bd->consultar($consulta);

            // Creamos objetos y los devolvemos en la respuesta.
            $preguntas = [];
            foreach ($resultado as $pregunta) {
                $preguntas[count($preguntas)] = new PreguntaGeneral($pregunta);
            }
            RespuestaHelper::enviarRespuesta(200, $preguntas);
        } else {
            RespuestaHelper::enviarRespuesta(401);
        }
    }

    /**
     * Obtener detalle una pregunta por su id.
     *
     * @param integer $id id de la pregunta.
     * @return void
     */
    function obtener(int $id): void
    {
        $sesion = new SesionService();
        // Comprobamos que el usuario tiene la sesión abierta y que su rol es admin.
        if (Rol::Administrador->value === $sesion->getIdRol()) {
            $bd = new BaseDatosService();
            // Obtenemos el detalle de la pregunta.
            $consultaPregunta = "SELECT p.id, p.titulo, p.esPublica, c.id idCategoria, c.img imgCategoria, c.nombre nombreCategoria FROM preguntas p JOIN categorias c ON p.idCategoria=c.id WHERE p.id=$id";
            $resultadoPregunta = $bd->consultar($consultaPregunta);

            // Obtenemos el detalle de las respuestas de la pregunta.
            $consultaRespuestas = "SELECT * FROM respuestas WHERE idPregunta=$id";
            $resultadoRespuestas = $bd->consultar($consultaRespuestas);

            // Creamos los objetos en base a los modelos y devolvemos la pregunta.
            $respuestas = [];
            foreach ($resultadoRespuestas as $respuesta) {
                $respuestas[count($respuestas)] = new RespuestaEdicion($respuesta);
            }

            $pregunta = new PreguntaEdicion(array_merge($resultadoPregunta[0], ['respuestas' => $respuestas]));
            RespuestaHelper::enviarRespuesta(200, $pregunta);
        } else {
            RespuestaHelper::enviarRespuesta(401);
        }
    }

    /**
     * Modifica una pregunta a través de su id y sus nuevas propiedades.
     *
     * @param integer $id id de la pregunta a modificar.
     * @param PreguntaEdicion $pregunta contenido de la pregunta a intercambiar.
     * @return void
     */
    function modificar(int $id, PreguntaEdicion $pregunta): void
    {
        $sesion = new SesionService();
        // Comprobamos que el usuario tiene la sesión abierta y que su rol es admin.
        if (Rol::Administrador->value === $sesion->getIdRol()) {
            if ($id === $pregunta->id) {
                $bd = new BaseDatosService();

                // Insertamos una categoría nueva si se ha utilizado una nueva categoría.
                if ($pregunta->idCategoria === 0) {
                    $columnasAInsertar = ['nombre'];
                    $valoresAInsertar = ["'$pregunta->nombreCategoria'"];
                    if (isset($pregunta->imgCategoria)) {
                        $columnasAInsertar[count($columnasAInsertar)] = 'img';
                        $valoresAInsertar[count($valoresAInsertar)] = "'$pregunta->imgCategoria'";
                    }
                    $sentenciaCategoria = "INSERT into categorias (" . implode(',', $columnasAInsertar) . ") VALUES (" . implode(',', $valoresAInsertar) . ")";
                    $bd->ejecutar($sentenciaCategoria);

                    // Guardamos en el mismo objeto el id de la categoría insertada.
                    $pregunta->idCategoria = (int) $bd->obtenerUltimoId();
                }

                // Actualizamos la pregunta con los nuevos valores.
                $sentenciaPregunta = "UPDATE preguntas SET 
                    titulo='" . addslashes($pregunta->titulo) . "',
                    esPublica=" . ($pregunta->esPublica ? 1 : 0) . ",
                    idCategoria=$pregunta->idCategoria
                    WHERE id=$id";
                $bd->ejecutar($sentenciaPregunta);

                // Actualizamos las respuestas de la pregunta con los nuevos valores.
                foreach ($pregunta->respuestas as $respuesta) {
                    $sentenciaRespuesta = "UPDATE respuestas SET titulo='" . addslashes($respuesta->titulo) . "', esCorrecta=" . ($respuesta->esCorrecta ? 1 : 0) . " WHERE id=$respuesta->id";
                    $bd->ejecutar($sentenciaRespuesta);
                }

                RespuestaHelper::enviarRespuesta(204);
            } else {
                RespuestaHelper::enviarRespuesta(400, new Error('Los id no coinciden.'));
            }
        } else {
            RespuestaHelper::enviarRespuesta(401);
        }
    }

    /**
     * Establece la visibilidad de la pregunta dada por su id.
     *
     * @param boolean $esPublica nuevo valor de visibilidad.
     * @param integer $id id de la pregunta.
     * @return void
     */
    function establecerVisibilidad(bool $esPublica, int $id): void
    {
        $sesion = new SesionService();
        // Comprobamos que el usuario tiene la sesión abierta y que su rol es admin.
        if (Rol::Administrador->value === $sesion->getIdRol()) {
            $bd = new BaseDatosService();
            // Actualiza el valor de la visibilidad de la pregunta.
            $sentencia = "UPDATE preguntas SET esPublica=" . ($esPublica ? 1 : 0) . " WHERE id=$id";
            $bd->ejecutar($sentencia);

            RespuestaHelper::enviarRespuesta(204);
        } else {
            RespuestaHelper::enviarRespuesta(401);
        }
    }

    function exportar(array $idCategorias, string $tipo): void
    {
        $sesion = new SesionService();
        if ($sesion->haySesion()) {
            $bd = new BaseDatosService();
            // Obtenemos todas las preguntas de las categorías dadas.
            $consultaPreguntas = "SELECT p.id, p.titulo, c.nombre nombreCategoria, c.id idCategoria FROM preguntas p LEFT JOIN categorias c ON c.id=p.idCategoria WHERE c.id in (" . implode(',', $idCategorias) . ") AND p.esPublica=1";
            $resultadoPreguntas = $bd->consultar($consultaPreguntas);

            // Obtenemos las respuestas de las preguntas de las categorías dadas.
            $consultaRespuestas = "SELECT r.id, r.titulo, r.idPregunta, r.esCorrecta FROM respuestas r JOIN preguntas p ON p.id=r.idPregunta WHERE p.idCategoria in (" . implode(',', $idCategorias) . ") AND p.esPublica=1";
            $resultadoRespuestas = $bd->consultar($consultaRespuestas);

            // Creamos objetos en base a los modelos a partir de los datos obtenidos.
            $preguntas = [];
            foreach ($resultadoPreguntas as $pregunta) {
                // Buscamos y adjuntamos las respuestas de cada pregunta.
                $respuestas = [];
                foreach ($resultadoRespuestas as $respuesta) {
                    if ($respuesta['idPregunta'] === $pregunta['id']) {
                        $respuestas[count($respuestas)] = new RespuestaEdicion($respuesta);
                    }
                };
                // Añadirmos la pregunta completa al listado de preguntas a devolver.
                $preguntas[count($preguntas)] = new Pregunta(array_merge($pregunta, ['respuestas' => $respuestas]));
            }
            switch ($tipo) {
                case 'xml':
                    $preguntasXml = new SimpleXMLElement('<preguntas></preguntas>');
                    foreach ($preguntas as $pregunta) {
                        $preguntaXml = $preguntasXml->addChild('pregunta');
                        $preguntaXml->addAttribute('id', $pregunta->id);
                        $preguntaXml->addAttribute('idCategoria', $pregunta->idCategoria);
                        $preguntaXml->addAttribute('nombreCategoria', $pregunta->nombreCategoria);
                        $preguntaXml->addChild('titulo', htmlspecialchars($pregunta->titulo));
                        $respuestasXml = $preguntaXml->addChild('respuestas');
                        foreach ($pregunta->respuestas as $respuesta) {
                            $respuestaXml = $respuestasXml->addChild('respuesta');
                            $respuestaXml->addAttribute('id', $respuesta->id);
                            $respuestaXml->addAttribute('esCorrecta', $respuesta->esCorrecta ? 1 : 0);
                            $respuestaXml->addChild('titulo', htmlspecialchars($respuesta->titulo));
                        }
                    }
                    $ruta = tempnam(sys_get_temp_dir(), 'preguntas.xml');
                    $archivo = fopen($ruta, 'w+');
                    fwrite($archivo, $preguntasXml->asXML());
                    fclose($archivo);
                    RespuestaHelper::enviarArchivo($ruta, 'preguntas.xml');
                    break;
                case 'json':
                    $ruta = tempnam(sys_get_temp_dir(), 'preguntas.json');
                    $archivo = fopen($ruta, 'w+');
                    fwrite($archivo, json_encode($preguntas));
                    fclose($archivo);
                    RespuestaHelper::enviarArchivo($ruta, 'preguntas.json');
                    break;
                default:
                    RespuestaHelper::enviarRespuesta(400, new Error('Tipo no soportado, solo soporta XML o JSON'));
            }
        } else {
            RespuestaHelper::enviarRespuesta(401);
        }
    }
}
