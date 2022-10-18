<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;


use App\Cart\CartService;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class PurchaseConfirmationController extends AbstractController {

        public function __construct(CartService $cartService, EntityManagerInterface $em)
        {
            $this->cartService = $cartService;
            $this->em = $em;
        }
        /**
         * @Route("/purchase/confirm", name="purchase_confirm")
         * @IsGranted("ROLE_USER", message= "Vous devez être connecté pour confirmer une commande")
         */
        public function confirm(Request $request ){
            
            $form = $this->createForm(CartConfirmationType::class);

            $form->handleRequest($request);

            if (!$form->isSubmitted()) {
                $this->addFlash('warning',' Vous devez remplir le formulaire de confirmation');
                
                return $this->redirectToRoute('cart_show');
            }

            $user = $this->getUser();

            $cartItems= $this->cartService->getDetailedCartItems();

            if (count($cartItems) === 0) {
                $this->addFlash('warning', 'Vous ne pouvez confirmer une commande avec un panier vide');
                return $this->redirectToRoute('cart_show');
            }

            /** @var Purchase */
            $purchase = $form->getData();

            $purchase->setUser($user)
                    ->setPurchaseAt(new DateTimeImmutable())
                    ->setTotal($this->cartService->getTotal());

            $this->em->persist($purchase);

            

            foreach ($this->cartService->getDetailedCartItems() as $cartItem) {
                $purchaseItem= new PurchaseItem;
                $purchaseItem->setPurchase($purchase)
                        ->setProduct($cartItem->product)
                        ->setProductName($cartItem->product->getPrice())
                        ->setQuantity($cartItem->qty)
                        ->setTotal($cartItem->getTotal())
                        ->setProductPrice($cartItem->product->getPrice());

                    
                        $this->em->persist($purchaseItem);

            }

           
            $this->em->flush();

            $this->cartService->empty();

            $this->addFlash('success', "La commande a bien été enregistrée");
            return $this->redirectToRoute('purchase_index');
        }
}