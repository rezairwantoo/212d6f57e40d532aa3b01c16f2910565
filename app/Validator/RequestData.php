<?php
namespace App\Validator;

class RequestData {
    
    const SendEmail = [
        'from' => 'required|email',
        'to' => 'required|email'
    ];
}