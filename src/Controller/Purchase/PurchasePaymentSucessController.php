<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PurchasePaymentSucessController extends AbstractController{

    /**
     * @Route("/purchase/confirmation/{id}", name="purchase_payment_success")
     * @IsGranted("ROLE_USER")
     * 
     */
    public function success($id, PurchaseRepository $purchaseRepository, EntityManagerInterface $em, CartService $cartService){

        // Je récupe ma commande
        $purchase = $purchaseRepository->find($id);

        if(!$purchase || 
        ($purchase && $purchase->getUser() !== $this->getUser()) || 
        ($purchase && $purchase->getStatus() === Purchase::STATUS_PAID)) {


            $this->addFlash('warning', "La commande n'existe pas ");
            return $this->redirectToRoute("purchase_index");
        }

        // Je l'as fais passer au statut payée

        $purchase->setStatus(Purchase::STATUS_PAID);
        $em->flush();
        $cartService->empty();
        // j'envois un flash avec la liste des commandes

        $this->addFlash('success', "La commande a été payée et confirmée");
        return $this->redirectToRoute("purchase_index");

    }

}