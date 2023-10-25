<?php
namespace App\Validator;

use App\Constant\ValidatorMessages;
use App\Constant\HttpStatus;

class Validator {
    private $rules;
    private $errGroups;

    public function __construct($arrValidator) {
        $this->rules = $arrValidator;
        $this->errGroups = [];
    }

    public function Validate($request) {
        foreach ($this->rules as $key => $value) {
            $this->checkRules($key, $value, $request[$key]);
        }

        if (count($this->errGroups) > 0) {
            $dataError = [
                "errors" => $this->errGroups,
                "messages" => HttpStatus::BadRequestText,
                "status" => HttpStatus::BadRequest,
                "data" => []
            ];
            return $dataError;
        }
        return [];
    }

    private function checkRules($key, $rule, $req) {
        $ruleList = explode("|", $rule);
        foreach ($ruleList as $keyRule => $valueRule) {
            switch ($valueRule) {
                case 'required':
                    $this->required($key, $req);
                    break;
                case 'email':
                    $this->email($key, $req);
                    break;
                default:
                    break;
            }
        }
    }

    private function required($key, $valueRule) {
        if (isset($valueRule) && empty($valueRule)) {
            array_push($this->errGroups, $this->setErrorField($key, ValidatorMessages::Required));
        }
    }

    private function email($key, $valueRule) {
        $email = filter_var($valueRule, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errGroups, $this->setErrorField($key, ValidatorMessages::InvalidEmail));
        } 
    }

    private function setErrorField($key, $errString) {
        return [
            "field" => $key,
            "error" => $errString
        ];
    }

}

?>