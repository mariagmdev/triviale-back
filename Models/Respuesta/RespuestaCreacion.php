<?php

namespace Models\Respuesta;

/**
 * Modelo básico de Respuesta para la creación de esta.
 */
class RespuestaCreacion
{
    public bool $esCorrecta;
    public string $titulo;

    function __construct(array $respuesta)
    {
        $this->esCorrecta = $respuesta['esCorrecta'];
        $this->titulo = $respuesta['titulo'];
    }
}
