<?php

namespace Enums;

/**
 * Enumerado de roles. Debe coincidir con los ids de la base de datos, pero los
 * guardamos aquí para ahorrarnos es dependencia.
 */
enum Rol: int
{
    case Usuario = 1;
    case Administrador = 2;
}
