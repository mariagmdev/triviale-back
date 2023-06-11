<?php

namespace Models\Respuesta;

/**
 * Modelo básico de Respuesta para la edición de esta.
 */
class RespuestaEdicion extends Respuesta
{
    public bool $esCorrecta;

    function __construct(array $respuesta)
    {
        parent::__construct($respuesta);
        $this->esCorrecta = $respuesta['esCorrecta'];
    }
}
