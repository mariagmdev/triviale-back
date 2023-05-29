<?php

namespace Services;

class SesionService
{
    function getId(): int|null
    {
        return isset($_SESSION['id']) ? $_SESSION['id'] : null;
    }
    function setId(int $id): void
    {
        $_SESSION['id'] = $id;
    }
    function getIdRol(): int|null
    {
        return isset($_SESSION['idRol']) ? $_SESSION['idRol'] : null;
    }
    function setIdRol(int $idRol): void
    {
        $_SESSION['idRol'] = $idRol;
    }
    
    function iniciarSesion(): void
    {
        session_start();
    }
    function haySesion(): bool
    {
        return isset($_SESSION['id']) ? true : false;
    }
}