<?php

namespace Models\Respuesta;

class RespuestaEdicion extends Respuesta
{
    public bool $esCorrecta;

    function __construct(array $respuesta)
    {
        parent::__construct($respuesta);
        $this->esCorrecta = $respuesta['esCorrecta'];
    }
}
