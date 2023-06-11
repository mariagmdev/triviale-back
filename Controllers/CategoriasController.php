<?php

namespace Controllers;

use Helpers\RespuestaHelper;
use Models\Categoria\Categoria;
use Models\Categoria\CategoriaPartida;
use Services\BaseDatosService;
use Services\SesionService;

class CategoriasController
{
    function listar(): void
    {
        $sesion = new SesionService();
        if ($sesion->haySesion()) {
            $bd = new BaseDatosService();
            $query = "SELECT * from categorias";
            $resultado = $bd->consultar($query);
            $categorias = [];
            foreach ($resultado as $categoria) {
                array_push($categorias, new Categoria($categoria));
            }
            RespuestaHelper::enviarRespuesta(200, $categorias);
        } else {
            RespuestaHelper::enviarRespuesta(401);
        }
    }

    function listarCategoriasPartida(): void
    {
        $sesion = new SesionService();
        if ($sesion->haySesion()) {
            $bd = new BaseDatosService();
            $query = "SELECT COUNT(p.id) cantidadPreguntas, c.* FROM categorias c JOIN preguntas p ON p.idCategoria=c.id WHERE p.esPublica=1 GROUP BY c.id";
            $resultado = $bd->consultar($query);
            $categorias = [];
            foreach ($resultado as $categoria) {
                array_push($categorias, new CategoriaPartida($categoria));
            }
            RespuestaHelper::enviarRespuesta(200, $categorias);
        } else {
            RespuestaHelper::enviarRespuesta(401);
        }
    }
}
