<?php

namespace Models\Usuario;

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
