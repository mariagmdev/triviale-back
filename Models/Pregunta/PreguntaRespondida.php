<?php

namespace Models\Pregunta;

/**
 * Modelo básico de Pregunta que contiene la respuesta respondida.
 */
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
