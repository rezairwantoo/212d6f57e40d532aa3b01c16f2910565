<?php
namespace App\Controllers;

use App\Controllers\Controller;
use Gac\Routing\Request;
use App\Validator\RequestData;
use App\Validator\Validator;
use App\Usecase\MailUsecase;
use App\Models\Mail;

class EmailController extends Controller {

    public function __construct() {
    }

    public function SendEmail(Request $request)
    {
        $validator = new Validator(RequestData::SendEmail);
        $reqData = [
            "from" => $request->get("from"),
            "to" => $request->get("to"),
            "cc" => $request->get("cc"),
            "bcc" => $request->get("bcc"),
            "subject" => $request->get("subject"),
            "body" => $request->get("body"),
            "user_id" => rand(0, 100)
        ];

        $failed = $validator->Validate($reqData);
        if (count($failed) > 0) {
            return $this->jsonErrrorsResponse($failed, $failed['status']);
        }

        $mailUc = new MailUsecase;
        $resp = $mailUc->Create($reqData);
        return $this->jsonResponse($resp);
    }
}