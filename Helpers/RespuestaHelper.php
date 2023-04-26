<?php

namespace Helpers;

abstract class RespuestaHelper
{

    static function enviarRespuesta(int $codigo, mixed $body = null): void
    {
        http_response_code($codigo);
        if (isset($body)) {
            echo json_encode($body); // Lo pasamos a formato JSON para que angular lo entienda.
        }
        exit();
    }
}