<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InformationsController extends AbstractController
{
    /**
     * @Route("/informations/informationslegales", name="infos_legal")
     */
    public function legal(): Response
    {
        return $this->render('informations/informations.html.twig');
    }

     /**
     * @Route("/informations/livraison", name="infos_livraisons")
     */
    public function livraison(): Response
    {
        return $this->render('informations/livraisons.html.twig');
    }

     /**
     * @Route("/informations/conditionsgeneralesdeventes", name="infos_cgv")
     */
    public function cgv(): Response
    {
        return $this->render('informations/cgv.html.twig');
    }
}
