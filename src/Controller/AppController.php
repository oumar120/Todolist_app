<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Form\AgendaType;
use App\Repository\AgendaRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{
    #[Route('/app', name: 'app_app')]
    public function index(): Response
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
    #[Route('/home',name:'home')]
    public function home(Request $request,ObjectManager $manager){
            $agenda=new Agenda();
        $form=$this->createForm(AgendaType::class,$agenda);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           $manager->persist($agenda);
           $manager->flush();
           return $this->redirectToRoute('home');
        }
          $query=$manager->createQuery('select A from App\Entity\Agenda A');
          $donnee=$query->getResult();
        return $this->render('/app/index.html.twig',[
            'form'=>$form->createView(),
              'data'=>$donnee]);
    }
    #[Route('/update/{id}',name:"update")]
    public function update(Agenda $agenda){
            $form=$this->createFormBuilder($agenda)
                       ->add('author')
                       ->add('content')
                       ->getForm();
            dump($form);
        return $this->render('/app/index.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    #[Route('/delete/{id}',name:'delete')]
    public function delete(Agenda $agenda,ObjectManager $manager){
          $manager->remove($agenda);
    }
}
