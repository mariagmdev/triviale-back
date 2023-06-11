<?php

namespace Models\Usuario;

/**
 * Modelo básico de Usuario para el inicio de sesión.
 */
class UsuarioInicio
{
    public $nombre;
    public $clave;

    function __construct(array $usuario)
    {
        $this->nombre = $usuario['nombre'];
        $this->clave = $usuario['clave'];
    }
}
