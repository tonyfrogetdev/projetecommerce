<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{

    private $em;
    
    public function __construct(EntityManagerInterface $entityManager){
        $this->em = $entityManager ;
    }
    /**
     * @Route("/admin/inscription/liste", name="inscription_liste")
     */
    public function index(): Response
    {
            $membres = $this->em->getRepository(User::class)->findAll();
            return $this->render('registration/index.html.twig', [
                'membres' => $membres
            ]);
    }

     /**
     * @Route("/mesinformations/{id}", name="voir_infos")
     */
    public function voirinformation($id): Response
    {   
        $user = $this->getUser($id);

        $user = $this->em->getRepository(User::class)->find($id);
        
        return $this->render('registration/voir.html.twig', [
            'users' => $user
        ]);
    }

    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(["ROLE_USER"]);

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('homepage');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/information/{id}/edit", name="modifier_membre")
     */
    public function editregister($id, Request $request, UserRepository $userRepository, EntityManagerInterface $em)
    {

        $user = $this->getUser($id);
        $user = $this->em->getRepository(User::class)->find($id);
        $form = $this->createForm(RegistrationFormType::class, $user);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form-> isValid()) {
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('modifier_membre');

            
        }

        return $this->render('registration/edit.html.twig', [
            'registrationForm' => $form->createView(),
            'useredit' => $user,
        ]);
    }
    
}
