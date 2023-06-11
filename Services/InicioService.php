<?php

namespace Services;

/**
 * Clase encargada de inicializar recursos, constantes y configuraciones.
 * Debe ser instanciada al comienzo de cada punto de entrada del servidor.
 */
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

    /**
     * Crear constantes de conexión de la base de datos.
     *
     * @return void
     */
    function configurarConexionBD(): void
    {
        define('DIRECCION', 'localhost');
        define('USUARIO', 'root');
        define('PASSWORD', '');
        define('BD', 'triviale');
    }

    /**
     * Definir el tipo de respuesta que se devuelve.
     * En la mayoría de casos es json.
     *
     * @return void
     */
    function configurarCabecera(): void
    {
        header('Content-Type: application/json; charset=utf-8');
    }

    /**
     * Crear constantes de configuración para reCaptcha.
     *
     * @return void
     */
    function configurarRecaptcha(): void
    {
        define('RECAPTCHA_API', 'https://www.google.com/recaptcha/api/siteverify');
        define('RECAPTCHA_SECRET', '6Ldio2QmAAAAADEtSXKeRl01kmh7Ok4-FKa6z5JH');
    }
}
