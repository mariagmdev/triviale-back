<?php

namespace Models\Usuario;

class UsuarioPuntuacion
{
    public $nombre;
    public $puntos;

    function __construct(array $usuario)
    {
        $this->nombre = $usuario['nombre'];
        $this->puntos = (int)$usuario['puntos'];
    }
}