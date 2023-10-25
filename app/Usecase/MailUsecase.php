<?php

namespace App\Usecase;

use Illuminate\Http\Request;
use App\Repository\Mail\MailRepository;
use App\Constant\HttpStatus;
use App\Models\Mail;

class MailUsecase
{
    private $mailRepo;
    public function __construct() {
        $this->mailRepo = new MailRepository(Mail::table);
    }

    public function Create($request){
        return $this->mailRepo->Insert($request);

    }
}
