<?php

namespace Controllers;

use Helpers\RespuestaHelper;
use Models\Categoria\Categoria;
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
}
