<?php

namespace Helpers;

/**
 * Clase auxiliar para el procesamiento de la petición.
 */
abstract class PeticionHelper
{
    /**
     * Obtiene el cuerpo de la petición y lo decodifica a JSON ya que es el formato utilizado.
     */
    static function getBody(): array|null
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}
