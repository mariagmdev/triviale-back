<?php

namespace Models\Respuesta;

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
