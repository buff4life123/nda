<?php
namespace App\Service;

use EmailValidator\EmailValidator;

class Validations
{
    //validade user email
    public function noFakeEmails($email) {
        $invalid = 0;
        
        if($email){
            $validator = new \EmailValidator\Validator();
            $validator->isEmail($email) ? false : $invalid = 1;
            $validator->isSendable($email) ? false : $invalid = 1;
            $validator->hasMx($email) ? false : $invalid = 1;
            $validator->hasMx($email) != null ? false : $invalid = 1;
            $validator->isValid($email) ? false : $invalid = 1;
        }
    
        return $invalid;
	}
	
	// validade user telephone
	public function validatePhone($string) {
		$numbersOnly = preg_replace("[^0-9]", "", $string);
		$numberOfDigits = strlen($numbersOnly);
		$invalid = $numberOfDigits >= 9 && $numberOfDigits <= 14 ? 0 : 1;
		
		return $invalid;
	}

}
