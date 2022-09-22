<?php

namespace App\Controller;

use App\Taxes\Calculator;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HelloController extends AbstractController
{

   public function __construct(Calculator $calculator)
   {
      $this->calculator = $calculator;
   }

   /**
    * @Route("/hello/{prenom?World}", name="hello")
    */

   public function hello($prenom = "World", LoggerInterface $logger, Calculator $calculator, Environment $twig)
   {
    

      $logger->info("Mon message de log!");

      $tva = $calculator->calcul(100);

      dump($tva);
      return new Response("Hello $prenom");
   }
}
