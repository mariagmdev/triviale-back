<?php

namespace Models\Pregunta;

/**
 * Modelo básico de Pregunta para la creación de esta.
 */
class PreguntaCreacion
{
    public string $titulo;
    public string $categoria;
    public int $idCategoria;
    public string $imgCategoria;
    public array $respuestas;

    function __construct(array $pregunta)
    {
        $this->titulo = $pregunta['titulo'];
        $this->categoria = $pregunta['categoria'];
        $this->idCategoria = (int)$pregunta['idCategoria'];
        if (isset($pregunta['imgCategoria'])) {
            $this->imgCategoria = $pregunta['imgCategoria'];
        }
        $this->respuestas = $pregunta['respuestas'];
    }
}
