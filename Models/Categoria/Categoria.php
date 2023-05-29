<?php

namespace Models\Categoria;

class Categoria
{
    public int $id;
    public string $nombre;

    function __construct(array $categoria)
    {
        $this->id = (int)$categoria['id'];
        $this->nombre = $categoria['nombre'];
    }
}
