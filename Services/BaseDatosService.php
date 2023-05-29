<?php

namespace Services;

use mysqli;

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

    public function consultar(string $consulta): array
    {
        $resulset = $this->con->query($consulta);
        $registros = $resulset->fetch_all(MYSQLI_ASSOC);
        return $registros;
    }

    public function ejecutar(string $sentencia): bool
    {
        $haIdoBien = $this->con->query($sentencia);
        return $haIdoBien ? true : false;
    }

    public function obtenerUltimoId(): int | string
    {
        return $this->con->insert_id;
    }
}
