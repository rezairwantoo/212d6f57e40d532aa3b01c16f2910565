<?php
namespace App\Controllers;

use App\Controllers\Controller;
use Gac\Routing\Request;

class WelcomeController extends Controller {

    public function __construct() {
    }

    public function Welcome(Request $request)
    {
        $data = [
            "messages" => "welcome"
        ];
        return $this->jsonResponse($data);
    }
}