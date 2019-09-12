<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 12.09.2019
 * Time: 22:56
 */

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;

class TestController
{
    public function Hello(){
        return new Response("Hello!");
    }
}