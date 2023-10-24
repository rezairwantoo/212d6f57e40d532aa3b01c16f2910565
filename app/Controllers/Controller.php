<?php

namespace App\Controllers;

use Gac\Routing\Response;

class Controller
{
    public function jsonResponse($data) {
        return Response::withHeader("Content-Type", "application/json")::
        withStatus(200, 'OK')::
        withBody($data)::
        send();
    }

    public function jsonErrrorsResponse($data, $errStatus) {
        return Response::withHeader("Content-Type", "application/json")::
        withStatus($errStatus, 'error')::
        withBody($data)::
        send();
    }
}
