<?php

namespace Services;

class InicioService
{
    function __construct()
    {
        $this->configurarCabecera();
        $this->configurarConexionBD();
    }

    function configurarConexionBD(): void
    {
        define('DIRECCION', 'localhost');
        define('USUARIO', 'root');
        define('PASSWORD', '');
        define('BD', 'triviale');
    }

    function configurarCabecera(): void
    {
        header('Content-Type: application/json; charset=utf-8'); // Siempre devolvemos un json.
    }
}