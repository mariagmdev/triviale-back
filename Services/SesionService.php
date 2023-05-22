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
    function iniciarSesion(): void
    {
        session_start();
    }
    function haySesion(): bool
    {
        return isset($_SESSION['id']) ? true : false;
    }
}