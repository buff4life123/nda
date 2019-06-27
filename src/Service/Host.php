<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class Host
{
       /*check the current host*/
       public function getHost(Request $request){

        if(preg_match('/10.0.9/i', $request->getHttpHost()))
            $host = 'http://'.$request->getHttpHost();

        else if(preg_match('/demo/i', $request->getHttpHost()))
            $host = 'https://demo.nauticdrive-algarve.com/';

        else
            $host = 'https://nauticdrive-algarve.com/';

        return $host;
    }

}
