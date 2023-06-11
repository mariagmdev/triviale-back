<?php

namespace Services;

/**
 * Clase encargada de gestionar la sesión del usuario.
 */
class SesionService
{
    /**
     * Obtiene el id del usuario si existe.
     *
     * @return integer|null
     */
    function getId(): int|null
    {
        return isset($_SESSION['id']) ? $_SESSION['id'] : null;
    }

    /**
     * Establece el id del usuario dado en la sesión.
     *
     * @param integer $id id de usuario
     * @return void
     */
    function setId(int $id): void
    {
        $_SESSION['id'] = $id;
    }

    /**
     * Obtiene el id de rol del usuario si existe.
     *
     * @return integer|null
     */
    function getIdRol(): int|null
    {
        return isset($_SESSION['idRol']) ? $_SESSION['idRol'] : null;
    }

    /**
     * Establece el id de rol del usuario dado en la sesión.
     *
     * @param integer $idRol
     * @return void
     */
    function setIdRol(int $idRol): void
    {
        $_SESSION['idRol'] = $idRol;
    }

    /**
     * Inicializa la sesión de PHP.
     *
     * @return void
     */
    function iniciarSesion(): void
    {
        session_start();
    }

    /**
     * Devuelve si existe una sesión iniciada por un usuario o no.
     *
     * @return boolean
     */
    function haySesion(): bool
    {
        return isset($_SESSION['id']) ? true : false;
    }
}
