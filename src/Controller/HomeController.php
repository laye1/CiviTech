<?php
namespace App\Controller;



use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;



Class HomeController extends AbstractController{






    /**
     * @Route("/", name="home")
     */
    public function index():Response
    {

    return $this->render('acceuil/home.html.twig');

    }














}










