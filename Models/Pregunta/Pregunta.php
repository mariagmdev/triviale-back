<?php

namespace Models\Pregunta;

/**
 * Modelo básico de Pregunta.
 */
class Pregunta
{
    public int $id;
    public string $titulo;
    public array $respuestas;
    public string $imgCategoria;
    public string $nombreCategoria;

    function __construct(array $pregunta)
    {
        $this->id = (int)$pregunta['id'];
        $this->titulo = $pregunta['titulo'];
        $this->respuestas = $pregunta['respuestas'];
        $this->imgCategoria = $pregunta['imgCategoria'];
        $this->nombreCategoria = $pregunta['nombreCategoria'];
    }
}
