<?php

namespace Models\Respuesta;

class Respuesta
{
    public int $id;
    public string $titulo;

    function __construct(array $respuesta)
    {
        $this->id = (int)$respuesta['id'];
        $this->titulo = $respuesta['titulo'];
    }
}