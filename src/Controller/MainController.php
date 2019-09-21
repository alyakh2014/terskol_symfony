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
use App\Entity\Section;
use App\Entity\Images;
use Doctrine\ORM\Query;

class MainController extends AbstractController
{
    public function Index(){
        $sections = [];
        $sectionDoctrine = $this->getDoctrine()->getRepository(Section::class)->findAllAsArray();
        $sectionsAr = $sectionDoctrine;//->getArrayResult();
        $imagesDoctrine = $this->getDoctrine()->getRepository(Images::class);
        $imagesAr = $imagesDoctrine->findAll();
        foreach ($imagesAr as $key=>$image){
            $sectionsAr[$image->getSectionId()]["Images"][] = $image->getPath();
        }
        return $this->render('main/index.html.twig', array('Sections'=>$sectionsAr));
    }


    public function Test(){

        return $this->render('main/test.html');
    }
}