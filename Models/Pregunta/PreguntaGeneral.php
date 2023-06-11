<?php

namespace Models\Pregunta;

/**
 * Modelo básico de Pregunta sin respuestas.
 */
class PreguntaGeneral
{
    public int $id;
    public string $titulo;
    public bool $esPublica;
    public string $nombreCategoria;
    public int $idCategoria;

    function __construct(array $pregunta)
    {
        $this->id = (int)$pregunta['id'];
        $this->titulo = $pregunta['titulo'];
        $this->esPublica = (bool) $pregunta['esPublica'];
        $this->nombreCategoria = $pregunta['nombreCategoria'];
        $this->idCategoria = (int)$pregunta['idCategoria'];
    }
}
