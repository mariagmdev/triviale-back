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
    public int $idCategoria;

    function __construct(array $pregunta)
    {
        $this->id = (int)$pregunta['id'];
        $this->titulo = $pregunta['titulo'];
        $this->respuestas = $pregunta['respuestas'];
        if (isset($pregunta['imgCategoria'])) {
            $this->imgCategoria = $pregunta['imgCategoria'];
        }
        $this->nombreCategoria = $pregunta['nombreCategoria'];
        $this->idCategoria = (int) $pregunta['idCategoria'];
    }
}
