<?php

namespace Models\Categoria;

/**
 * Modelo derivado de Categoría.
 */
class CategoriaPartida extends Categoria
{
    public int $cantidadPreguntas;

    function __construct(array $categoria)
    {
        parent::__construct($categoria);
        $this->cantidadPreguntas = (int)$categoria['cantidadPreguntas'];
    }
}
