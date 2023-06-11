<?php

namespace Services;

use mysqli;

/**
 * Clase encargada de toda la integración con la base de datos. Todas las consultas
 * y sentencias deberán ser lanzadas o ejecutadas desde este clase servicio.
 */
class BaseDatosService
{
    private mysqli $con;

    public function __construct()
    {
        @$con = new mysqli(DIRECCION, USUARIO, PASSWORD, BD);
        if (!$con->connect_errno) {
            $this->con = $con;
        } else {
            exit;
        }
    }

    /**
     * Realiza la consulta dada en la base de datos.
     *
     * @param string $consulta consulta a realizar.
     * @return array registros encontrados para la consulta.
     */
    public function consultar(string $consulta): array
    {
        $resulset = $this->con->query($consulta);
        $registros = $resulset->fetch_all(MYSQLI_ASSOC);
        return $registros;
    }

    /**
     * Ejecuta la sentencia dada en la base de datos.
     *
     * @param string $sentencia sentencia a ejecutar.
     * @return boolean si ha ido bien o no la sentencia
     */
    public function ejecutar(string $sentencia): bool
    {
        $haIdoBien = $this->con->query($sentencia);
        return $haIdoBien ? true : false;
    }

    /**
     * Obtiene el último id insertado en la base de datos.
     *
     * @return int|string
     */
    public function obtenerUltimoId(): int | string
    {
        return $this->con->insert_id;
    }
}
