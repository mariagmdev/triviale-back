<?php

namespace Models\Error;

class Error
{
    public string $mensaje;

    public function __construct(string $mensaje)
    {
        $this->mensaje = $mensaje;
    }
}