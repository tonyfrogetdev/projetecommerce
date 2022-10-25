<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contactmessage")
     */
    public function index(): Response
    {   

        $form= $this->createForm(ContactType::class);

        $contactForm = $form->createView();

        return $this->render('contact/index.html.twig', [
            'contactForm' => $contactForm
        ]);
    }
}
