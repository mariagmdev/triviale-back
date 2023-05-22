<?php

namespace Helpers;

abstract class PeticionHelper
{


    static function getBody(): array|null
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}