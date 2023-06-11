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

/**
 * Clase controladora que gestiona todo lo relacionado con la autenticación.
 */
class AuthController
{

    /**
     * Registra el usuario dado.
     *
     * @param UsuarioRegistro $usuario
     * @return void
     */
    function registrar(UsuarioRegistro $usuario): void
    {
        // Comprobar si ambas claves coinciden entre si.
        if ($usuario->clave !== $usuario->clave2) {
            $error = new Error('Las contraseñas no coinciden.');
            RespuestaHelper::enviarRespuesta(400, $error); //Bad request del cliente
        }

        $bd = new BaseDatosService();

        // Buscamos si existe algún usuario con el mismo nombre del registro.
        $consulta = "SELECT * FROM usuarios WHERE nombre='$usuario->nombre'";
        $resultado = $bd->consultar($consulta);
        if (empty($resultado)) {
            // Ciframos la clave del usuario e insertamos los datos.
            $usuario->clave = md5($usuario->clave);
            $sentencia = "INSERT INTO usuarios (nombre, clave, idRol) VALUES ('$usuario->nombre', '$usuario->clave', " . Rol::Usuario->value . ")";
            $bd->ejecutar($sentencia);

            // Creamos la sesión y devolvemos la sesión.
            $usuarioInicioSesion = new UsuarioInicioRes(['id' => $bd->obtenerUltimoId(), 'nombre' => $usuario->nombre, 'idRol' => Rol::Usuario->value]);
            $sesion = new SesionService();
            $sesion->setId((int) $usuarioInicioSesion->id);
            $sesion->setIdRol((int) $usuarioInicioSesion->idRol);
            RespuestaHelper::enviarRespuesta(200, $usuarioInicioSesion);
        } else {
            RespuestaHelper::enviarRespuesta(400, new Error("Ya existe este usuario."));
        }
    }

    /**
     * Inicia la sesión del usuario dado.
     *
     * @param UsuarioInicio $usuario
     * @return void
     */
    function iniciarSesion(UsuarioInicio $usuario): void
    {
        // Ciframos la clave y comprobamos que existe un usuario con ese nombre y clave cifrada.
        $usuario->clave = md5($usuario->clave);
        $bd = new BaseDatosService();
        $consulta = "SELECT id, nombre, idRol FROM usuarios WHERE nombre='$usuario->nombre' and clave='$usuario->clave'";
        $resultado = $bd->consultar($consulta);
        if (!empty($resultado)) {
            // Creamos la sesión y devolvemos la sesión.
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
