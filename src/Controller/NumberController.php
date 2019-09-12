<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 12.09.2019
 * Time: 22:56
 */

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;

class NumberController
{
    public function Number(){
        $number = mt_rand(0, 100);
        return new Response("<html><body><h1>Number: $number</h1></body></html>");
    }
}