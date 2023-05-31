<?php

namespace Models\Pregunta;

class PreguntaEdicion extends PreguntaGeneral
{
    public array $respuestas;

    function __construct(array $pregunta)
    {
        parent::__construct($pregunta);
        $this->respuestas = $pregunta['respuestas'];
    }
}
