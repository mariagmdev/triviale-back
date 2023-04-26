<?php

namespace Models\Usuario;

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