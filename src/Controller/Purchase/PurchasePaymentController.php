<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Repository\PurchaseRepository;
use App\Stripe\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;



Class PurchasePaymentController extends AbstractController{


    /**
     * @Route("/purchase/paiement/{id}", name="purchase_payment_form")
     */
    public function showCardForm($id, PurchaseRepository $purchaseRepository, StripeService $stripeService){

        $purchase = $purchaseRepository->find($id);

        if(!$purchase || 
        ($purchase && $purchase->getUser() !== $this->getUser()) || 
        ($purchase && $purchase->getStatus() === Purchase::STATUS_PAID)) {
            return $this->redirectToRoute('cart_show');
            # code...
        }

        \Stripe\Stripe::setApiKey('sk_test_51LuG0HAeLjIUesA8vc0dkjW25ITE1mknVOkbLYwppWPmtR7gOqBi8yp736VtzFtGexVbQgg3Jq0KbDgmdRjuzjFR00t0IbcHCB');

        $intent= $stripeService->getPaymentIntent($purchase);
        

        return $this->render('purchase/payment.html.twig', [
            'clientSecret' => $intent->client_secret,
            'purchase' => $purchase
        ]);
    }
}