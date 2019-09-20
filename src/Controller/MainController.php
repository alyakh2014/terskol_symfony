<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 12.09.2019
 * Time: 22:56
 */

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response,
    Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    public function Index(){
        return $this->render('main/index.html.twig', array());
    }
}