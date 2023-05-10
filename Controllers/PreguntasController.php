<?php

namespace Controllers;



class PreguntasController
{

    function obtenerXPreguntasAleatoriasPorCategorias()
    {
    }
    // function iniciarSesion(UsuarioInicio $usuario)
    // {
    //     $usuario->clave = md5($usuario->clave);
    //     $bd = new BaseDatosService();
    //     $consulta = "SELECT id, nombre, idRol FROM usuarios WHERE nombre='$usuario->nombre' and clave='$usuario->clave'";
    //     $resultado = $bd->consultar($consulta);
    //     if (!empty($resultado)) {
    //         RespuestaHelper::enviarRespuesta(200, new UsuarioInicioRes($resultado[0]));
    //     } else {
    //         $error = new Error('Usuario o contraseña incorrectos.');
    //         RespuestaHelper::enviarRespuesta(400, $error);
    //     }
    // }
}