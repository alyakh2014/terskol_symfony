<?php

namespace App\Controller;

use App\Entity\Images;
use App\Form\ImagesType;
use App\Repository\ImagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Section;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * @Route("/images")
 */
class ImagesController extends AbstractController
{
    /**
     * @Route("/", name="images_index", methods={"GET"})
     */
    public function index(ImagesRepository $imagesRepository): Response
    {
        return $this->render('images/index.html.twig', [
            'images' => $imagesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="images_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $image = new Images();
        $sections = [];
        $sectionDoctrine = $this->getDoctrine()->getRepository(Section::class);
        $sectionsAr = $sectionDoctrine->findAll();
        foreach($sectionsAr as $section){
            $sections[$section->getTitle()] = $section->getId();
        }
        $form = $this->createFormBuilder($image)
            ->add('Section_id', ChoiceType::class, array( 'choices'=>array($sections)))
            ->add('Path', FileType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sectionDir = $sectionDoctrine->findBy(["id" => $form['Section_id']->getData()])[0]->getCode();
            $fileName = $form['Path']->getData()->getClientOriginalName();
            $extractDir = 'img/'.$sectionDir;
            $image->setPath($extractDir.'/'.$fileName);
            $form['Path']->getData()->move($extractDir, $fileName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($image);
            $entityManager->flush();
            return $this->redirectToRoute('images_index');
        }

        return $this->render('images/new.html.twig', [
            'image' => $image,
            'form' => $form->createView(),
            'sections' => $sections,
            'method'=>'new'
        ]);
    }

    /**
     * @Route("/{id}", name="images_show", methods={"GET"})
     */
    public function show(Images $image): Response
    {
        return $this->render('images/show.html.twig', [
            'image' => $image,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="images_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Images $image): Response
    {
        //$image = new Images();
        $sections = [];
        $sectionDoctrine = $this->getDoctrine()->getRepository(Section::class);
        $sectionsAr = $sectionDoctrine->findAll();
        foreach($sectionsAr as $section){
            $sections[$section->getTitle()] = $section->getId();
        }
        $form = $this->createFormBuilder($image)
            ->add('Section_id', ChoiceType::class, array( 'choices'=>array($sections)))
            ->add('Path', HiddenType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('images_index');
        }

        return $this->render('images/edit.html.twig', [
            'image' => $image,
            'imgPath'=>$image->getPath(),
            'form' => $form->createView(),
            'method'=>'edit'
        ]);
    }

    /**
     * @Route("/{id}", name="images_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Images $image): Response
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($image);
            $entityManager->flush();
        }

        return $this->redirectToRoute('images_index');
    }

}
