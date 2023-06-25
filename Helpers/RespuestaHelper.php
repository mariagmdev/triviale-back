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

    static function enviarArchivo(string $ruta, string $nombre): void
    {
        http_response_code(200);
        // header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
        header("Cache-Control: public");
        header("Content-Type: application/octet-stream");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length:" . filesize($ruta));
        header("Content-Disposition: attachment; filename=$nombre");
        readfile($ruta);
        exit();
    }
}
