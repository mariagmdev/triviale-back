<?php

namespace Models\Pregunta;

class PreguntaRespondida
{
    public int $idPregunta;
    public int $idRespuesta;

    function __construct(array $pregunta)
    {
        $this->idPregunta = (int)$pregunta['idPregunta'];
        $this->idRespuesta = (int)$pregunta['idRespuesta'];
    }
}