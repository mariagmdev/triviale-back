<?php

namespace Models\Usuario;

/**
 * Modelo básico de Usuario para la respuesta del inicio de sesión.
 */
class UsuarioInicioRes
{
    public $id;
    public $nombre;
    public $idRol;

    function __construct(array $usuario)
    {
        $this->id = (int)$usuario['id'];
        $this->nombre = $usuario['nombre'];
        $this->idRol = (int)$usuario['idRol'];
    }
}
