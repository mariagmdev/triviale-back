<?php

namespace Models\Error;

/**
 * Modelo básico de Error.
 */
class Error
{
    public string $mensaje;

    public function __construct(string $mensaje)
    {
        $this->mensaje = $mensaje;
    }
}
