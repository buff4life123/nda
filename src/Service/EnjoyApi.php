<?php

// src/Service/ExperienceApi.php

namespace App\Service;

class EnjoyApi
{

    
    private $enj_api_key;
    private $url_api_key;
	
	public function __construct()
	{
        $this->enj_api_key = $_ENV['CL_API_ENJ'];
        $this->url_api_key = 'https://admin.enjoybesmart.pt/';
    }


    public function sendSMS($numbers, $sms){
        $url = $this->url_api_key.'api.php?api='.$this->enj_api_key."&action=sendsms&numbers=".$numbers."&sms=".$sms."&type=xml";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $xml = curl_exec($ch);
        curl_close($ch);

        return array(
            'url' => $this->url_api_key,
            'key' => $this->enj_api_key,
            'urlAll' => $url,
            'xml' => json_decode($xml)
        );
    }

}
