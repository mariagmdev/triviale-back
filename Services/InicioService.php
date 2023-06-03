<?php

namespace Services;

class InicioService
{
    function __construct()
    {
        $this->configurarCabecera();
        $this->configurarConexionBD();
        $this->configurarRecaptcha();
        $sesion = new SesionService();
        $sesion->iniciarSesion();
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

    function configurarRecaptcha(): void
    {
        define('RECAPTCHA_API', 'https://www.google.com/recaptcha/api/siteverify');
        define('RECAPTCHA_SECRET', '6Ldio2QmAAAAADEtSXKeRl01kmh7Ok4-FKa6z5JH');
    }
}
