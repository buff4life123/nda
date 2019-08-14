<?php

// src/Service/ExperienceApi.php

namespace App\Service;

class ExperienceApi
{

    
    private $exp_api_key;
    private $url_api_key;
	
	public function __construct()
	{
        $this->exp_api_key = $_ENV['CL_API_EXP'];
        $this->url_api_key = 'https://admin.experienceware.pt/';
    }

	/**
	 * @param 
	 $local String accepts('EN_en','PT_pt','ES_es','FR_fr')
	*/
	public function getProducts($local = null){
        
        //$l= null;
        switch($local) {
            case "en": $l="En_en";
            break;
            case "pt": $l="PT_pt";
            break;
            default: "erro";
        }

        $url = $this->url_api_key.'api/'.$this->exp_api_key.'/products/'.$l;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $p = curl_exec($ch);
        curl_close($ch);
		return array(
            'url' => $this->url_api_key,
            'key' => $this->exp_api_key,
            'products' => json_decode($p)
        );
	}

	/**
	 * @param 
	 $local String accepts('EN_en','PT_pt','ES_es','FR_fr')
	 $productId integer
	*/
	public function getProduct($local = null, $productId = null){

        switch($local) {
            case "en": $l="En_en";
            break;
            case "pt": $l="PT_pt";
            break;
            default: "erro";
        }

        $url = $this->url_api_key.'api/'.$this->exp_api_key.'/product/'.$productId.'/'.$l;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $p = curl_exec($ch);
        curl_close($ch);
		return array(
            'url' => $this->url_api_key,
            'key' => $this->exp_api_key,
            'products' => json_decode($p)
        );
    }
    
    public function getFixedTranslations($local = null){
        $url = $this->url_api_key.'api/'.$this->exp_api_key.'/translator/'.$local;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $p = curl_exec($ch);
        curl_close($ch);
 
        return array(
                'url' => $this->url_api_key,
                'key' => $this->exp_api_key,
                'products' => json_decode($p)
            );
 
    }

}
