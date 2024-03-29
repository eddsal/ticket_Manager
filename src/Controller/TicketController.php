<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/ticket")
 */
class TicketController extends AbstractController
{
  
 
    /**
     * @Route("/", name="ticket_index", methods={"GET"})
     */
    public function index(TicketRepository $ticketRepository)
    /*    public function index()*/
    {
        /*        $repo = $this->getDoctrine()->getRepository(Article::class);
        $repuser = $this->getDoctrine()->getRepository(User::class);*/
       if ($this->isGranted('ROLE_ADMIN')){
           $tickets = $ticketRepository->findAll();
           
           //    $user = $repuser->findAll();
           //    dump($user);
           return $this->render('ticket/index.html.twig', [
               
               'tickets' => $tickets,
               //'user'=>$user,
               ]);
            }
            else{
                $user = $this -> getUser();
                $sess =  $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
                
                //TODO
                //pas tout a fais compris
                //        if(!$sess){
                    //            $articles = $user->getArticles();
                    //        }else{
                        //            return $this->render('main/index.html.twig');
                        //        }
                        
                        //        return $this->render('main/index.html.twig', [
                            //            'controller_name' => 'MainController',
                            //            'articles' => $tickets,
                            //            'user'=>$user,
                            
                            //        ]);
                        }
                    }
                        
















    
    /**
     * @Route("/new", name="ticket_new", methods={"GET","POST"})
     */
    public function new(Request $request,TokenStorageInterface $tokenStorage ): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user =$tokenStorage->getToken()->getUser();
            $username = $user->getUsername();
            $ticket->setAuthor($username);

            $ticket ->setDate( new \DateTime('now'));


            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('ticket_index');
        }

        return $this->render('ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ticket_show", methods={"GET"})
     */
    public function show(Ticket $ticket): Response
    {
        return $this->render('ticket/show.html.twig', ['ticket' => $ticket]);
    }

    /**
     * @Route("/{id}/edit", name="ticket_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ticket $ticket): Response
    {
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ticket_index', ['id' => $ticket->getId()]);
        }

        return $this->render('ticket/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ticket_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Ticket $ticket): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ticket_index');
    }

    
     /**
     * @Route("/{id}", name="ticket_comment", methods={"update"})
     */
    public function comment(Request $request,TokenStorageInterface $tokenStorage ): Response
    {
        $comment = new comments();
        $form = $this->createForm(TicketType::class, $comment);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $user =$tokenStorage->getToken()->getUser();
        //     $username = $user->getUsername();
        //     $ticket->setAuthor($username);

        //     $ticket ->setDate( new \DateTime('now'));


        //     $entityManager->persist($ticket);
        //     $entityManager->flush();

        //     return $this->redirectToRoute('ticket_index');
        // }

        return $this->render('ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }
}
