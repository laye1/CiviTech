<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends AbstractController
{

    /**
     * @Route("/inscription", name="security_inscription")
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
     public function registration(Request $request, ObjectManager $manager,UserPasswordEncoderInterface $encoder){

     $user = new User();

     $form = $this->createForm(UserType::class, $user);

     $form->handleRequest($request);

     if($form->isSubmitted() && $form->isValid()){

         $hash=$encoder->encodePassword($user, $user->getPassword());

         $user->setPassword($hash);



         $manager->persist($user);
         $manager->flush();

         return $this->redirectToRoute('security_login');


     }

     return $this->render('admin/inscription.html.twig',array(
         'form' => $form->createView())
     );


     }


    /**
     * @Route("/connexion", name="security_login")
     */
     public  function Connexion(){

       return $this->render('admin/login.html.twig');



     }

     /**
      * @Route("/deconnexion", name="security_logout")
      */

     public function logout(){


     }







}
