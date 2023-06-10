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

            $usuarioInicioSesion = new UsuarioInicioRes(['id' => $bd->obtenerUltimoId(), 'nombre' => $usuario->nombre, 'idRol' => Rol::Usuario->value]);

            $sesion = new SesionService();
            $sesion->setId((int) $usuarioInicioSesion->id);
            $sesion->setIdRol((int) $usuarioInicioSesion->idRol);
            RespuestaHelper::enviarRespuesta(200, $usuarioInicioSesion);
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
            $sesion->setId((int) $resultado[0]['id']);
            $sesion->setIdRol((int) $resultado[0]['idRol']);
            RespuestaHelper::enviarRespuesta(200, new UsuarioInicioRes($resultado[0]));
        } else {
            $error = new Error('Usuario o contraseña incorrectos.');
            RespuestaHelper::enviarRespuesta(400, $error);
        }
    }
}