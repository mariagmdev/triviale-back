<?php

namespace Models\Pregunta;

class Pregunta
{
    public int $id;
    public string $titulo;
    public array $respuestas;

    function __construct(array $pregunta)
    {
        $this->id = (int)$pregunta['id'];
        $this->titulo = $pregunta['titulo'];
        $this->respuestas = $pregunta['respuestas'];
    }
}