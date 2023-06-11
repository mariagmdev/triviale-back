<?php

namespace Helpers;

/**
 * Clase auxiliar para el procesamiento de la respuesta.
 */
abstract class RespuestaHelper
{
    /**
     * Devuelve una respuesta según el código HTTP introducido y adjunta al cuerpo
     * de la respuesta si se ha recibido por parámetro.
     *
     * @param integer $codigo código de estado HTTP
     * @param mixed $body cuerpo del mensaje de respuesta. Es convertido a JSON.
     * @return void
     */
    static function enviarRespuesta(int $codigo, mixed $body = null): void
    {
        http_response_code($codigo);
        if (isset($body)) {
            echo json_encode($body); // Lo pasamos a formato JSON para que angular lo entienda.
        }
        exit();
    }
}
