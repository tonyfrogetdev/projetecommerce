<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PurchasePersister extends AbstractController{

    protected $security;
    protected $cartService;
    protected $em;

    public function __construct(Security $security, CartService $cartService, EntityManagerInterface $em)
    {
        $this->security=$security;
        $this->cartService=$cartService;
        $this->em=$em;
    }
            public function storePurchase(Purchase $purchase){
                // On intégre et on persist la purchase

                // On la lie le tout avec l'utilisateur connecté(La Security)

            $purchase->setUser($this->security->getUser())
            ->setPurchaseAt(new DateTimeImmutable())
            ->setTotal($this->cartService->getTotal());

    $this->em->persist($purchase);

    // On la lie avec les produits qui sont dans le panier(CartService)

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
    // la commande s'enregistre
    
    $this->em->flush();
            }
}