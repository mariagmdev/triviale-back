<?php

namespace Controllers;

use Helpers\RespuestaHelper;
use Models\Categoria\Categoria;
use Models\Categoria\CategoriaPartida;
use Services\BaseDatosService;
use Services\SesionService;

/**
 * Clase controladora que gestiona todo lo relacionado con la entidad de Categoría.
 */
class CategoriasController
{
    /**
     * Lista todas las categorías.
     *
     * @return void
     */
    function listar(): void
    {
        $sesion = new SesionService();
        // Comprobamos que el usuario tiene la sesión abierta.
        if ($sesion->haySesion()) {
            // Obtenemos las categorías de la base de datos.
            $bd = new BaseDatosService();
            $query = "SELECT * from categorias";
            $resultado = $bd->consultar($query);

            // Convertimos el array asociativo a un array de objetos para mandarlo en la respuesta.
            $categorias = [];
            foreach ($resultado as $categoria) {
                $categorias[count($categorias)] = new Categoria($categoria);
            }

            // Devolvemos 200 OK, con las categorías adjuntas en el cuerpo de la respuesta.
            RespuestaHelper::enviarRespuesta(200, $categorias);
        } else {
            RespuestaHelper::enviarRespuesta(401);
        }
    }

    /**
     * Lista todas las categorías con al menos una pregunta jugable y devuelve
     * un conteo de de preguntas por cada categoría.
     *
     * @return void
     */
    function listarCategoriasPartida(): void
    {
        $sesion = new SesionService();
        // Comprobamos que el usuario tiene la sesión abierta.
        if ($sesion->haySesion()) {
            // Obtenemos las categorías de la base de datos.
            $bd = new BaseDatosService();
            $query = "SELECT COUNT(p.id) cantidadPreguntas, c.* FROM categorias c JOIN preguntas p ON p.idCategoria=c.id WHERE p.esPublica=1 GROUP BY c.id";
            $resultado = $bd->consultar($query);

            // Convertimos el array asociativo a un array de objetos para mandarlo en la respuesta.
            $categorias = [];
            foreach ($resultado as $categoria) {
                $categorias[count($categorias)] = new CategoriaPartida($categoria);
            }

            // Devolvemos 200 OK, con las categorías adjuntas en el cuerpo de la respuesta.
            RespuestaHelper::enviarRespuesta(200, $categorias);
        } else {
            RespuestaHelper::enviarRespuesta(401);
        }
    }
}
