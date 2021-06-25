<?php

namespace App\Controller\Admin;



use App\Entity\Comment;
use App\Entity\Publication;
use App\Form\CommentType;
use App\Form\PublicationType;
use App\Repository\PublicationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;



class PublicationController extends AbstractController

{
    /**
     * @var PublicationRepository
     */


    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;


    public function __construct(PublicationRepository $repository, ObjectManager $em)
    {

        $this->repository = $repository;
        $this->em = $em ;

    }











    /**
     * @Route("/admin", name="admin.pubs.index")
     * @return Response
     */
    public function index(){

     $pubs = $this->repository->findAll();
     return $this->render('admin/index.html.twig',compact('pubs'));

    }


    /**
     * @Route("/admin/pubs/create" , name="admin.pubs.new")
     * @param Request $request
     * @return Response
     */

    public function new(Request $request){

        $publication = new Publication();
        $form=$this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->em->persist($publication);
            $this->em->flush();

            return $this->redirectToRoute('admin.pubs.index');
        }

        return $this->render('admin/new.html.twig',[

            'publication' => $publication,

            'form' => $form->createView()

        ]);






    }





    /**
     * @Route("/admin/pubs/{id}", name="admin.pubs.edit", methods="GET|POST")
     * @param Publication $publication
     * @param Request $request
     * @return Response
     */

    public function edit(Publication $publication,Request $request)
    {

        $form=$this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

       if($form->isSubmitted() and $form->isValid()){

            $this->em->flush();

            return $this->redirectToRoute('admin.pubs.index');
        }

        return $this->render('admin/edit.html.twig',[

            'publication' => $publication,

            'form' => $form->createView()

            ]);

    }


    /**
     * @param Publication $publication
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/admin/pubs/{id}", name="admin.pubs.delete", methods="DELETE")
     */

    public function delete(Publication $publication){

        $this->em->remove($publication);

        $this->em->flush();

        return $this->redirectToRoute('admin.pubs.index');



    }


    /**
     * @Route("/pubs/{id}",name="pubs.show")
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function show($id,Request $request):Response

    {
        $publication = new Publication();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()){

             $comment->setCreatedAt(new \DateTime())
                     ->setPublication($publication);
            $this->em->persist($comment);
            $this->em->flush();

            return $this->redirectToRoute('pubs.show',[
                'id' => $publication->getId()
            ]);


        }



        $publication = $this->repository->find($id);
        return $this->render('acceuil/show.html.twig',[

            'publication' => $publication,

            'pubs' => 'pubs',
            'commentForm' => $form->createView(),
        ]);

    }







    /**
     * @Route("/pubs", name="pubs")
     * @return Response
     */
    public function pub():Response
    {

        $pubs = $this->repository->findAll();

        return $this->render('admin/publication.html.twig',compact('pubs'));

    }







}