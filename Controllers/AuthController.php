<?php

namespace Controllers;

use Enums\Rol;
use Models\Error\Error;
use Helpers\RespuestaHelper;
use Models\Usuario\UsuarioInicio;
use Models\Usuario\UsuarioInicioRes;
use Models\Usuario\UsuarioRegistro;
use Services\BaseDatosService;
use Services\SesionService;

class AuthController
{

    function registrar(UsuarioRegistro $usuario)
    {
        // TODO:david Debería añadir más validación al back para todos los campos o al hacerlo en front puedo ignorarlo. 
        // Sería buena idea poner el email aunque de momento no me servirá para nada.
        //Validación de datos
        if ($usuario->clave !== $usuario->clave2) {
            $error = new Error('Las contraseñas no coinciden.');
            RespuestaHelper::enviarRespuesta(400, $error); //Bad request del cliente
        }
        $bd = new BaseDatosService();
        $consulta = "SELECT * FROM usuarios WHERE nombre='$usuario->nombre'";
        $resultado = $bd->consultar($consulta);
        if (empty($resultado)) {
            $usuario->clave = md5($usuario->clave);
            $sentencia = "INSERT INTO usuarios (nombre, clave, idRol) VALUES ('$usuario->nombre', '$usuario->clave', " . Rol::Usuario->value . ")";
            $bd->ejecutar($sentencia);
            RespuestaHelper::enviarRespuesta(204);
        }
    }

    function iniciarSesion(UsuarioInicio $usuario)
    {
        $usuario->clave = md5($usuario->clave);
        $bd = new BaseDatosService();
        $consulta = "SELECT id, nombre, idRol FROM usuarios WHERE nombre='$usuario->nombre' and clave='$usuario->clave'";
        $resultado = $bd->consultar($consulta);
        if (!empty($resultado)) {
            $sesion = new SesionService();
            $sesion->setId($resultado[0]['id']);
            RespuestaHelper::enviarRespuesta(200, new UsuarioInicioRes($resultado[0]));
        } else {
            $error = new Error('Usuario o contraseña incorrectos.');
            RespuestaHelper::enviarRespuesta(400, $error);
        }
    }
}