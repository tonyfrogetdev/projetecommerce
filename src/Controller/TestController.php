<?php

namespace App\Controller;

use App\Taxes\Calculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TestController extends AbstractController{

    public function __construct(Calculator $calculator){
        $this->calculator = $calculator;
    }
    public function index(){

        $tva = $this->calculator->calcul(100);
        dump($tva);
        var_dump("ça fonctionne");
        die();
    }

    /**
     * @Route("/test/{age<\d+>?0}", name="test", methods={"GETS", "POST"}, schemes={"http", "https"})
     */
 
    public function test(Request $request, $age){


        
        return new Response("Vous avez $age ans !");
} }