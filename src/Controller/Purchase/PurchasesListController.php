<?php

namespace App\Controller\Purchase;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PurchasesListController extends AbstractController {
   
    /**
     * @Route("purchases", name="purchase_index")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour accéder à vos commandes")
     */
    public function index(){
        // Assurer personne connecter sinon la rediriger vers la page d'accueil -> security

        /**
         * @var User
         */
        $user = $this->getUser();

        // Savoir qui est co -> security

        return $this->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
    }

}