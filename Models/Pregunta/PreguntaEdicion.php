<?php

namespace Models\Pregunta;

/**
 * Modelo básico de Pregunta para la edición de esta.
 */
class PreguntaEdicion extends PreguntaGeneral
{
    public array $respuestas;
    public string $imgCategoria;

    function __construct(array $pregunta)
    {
        parent::__construct($pregunta);
        $this->respuestas = $pregunta['respuestas'];
        if (isset($pregunta['imgCategoria'])) {
            $this->imgCategoria = $pregunta['imgCategoria'];
        }
    }
}
