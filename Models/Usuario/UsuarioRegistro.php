<?php

namespace Models\Usuario;

class UsuarioRegistro
{
    public $nombre;
    public $clave;
    public $clave2;

    function __construct(array $usuario)
    {
        $this->nombre = $usuario['nombre'];
        $this->clave = $usuario['clave'];
        $this->clave2 = $usuario['clave2'];
    }
}