<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {
    protected $session;
    protected $productRepository;
    public function __construct(SessionInterface $session, ProductRepository $productRepository)

        {
          $this->session = $session;
          $this->productRepository = $productRepository;
        }

        protected function getCart(): array{
            return $this->session->get('cart', []);
        }
    
        protected function saveCart(array $cart){
            $this->session->set('cart',$cart);
        }

    public function empty(){
        $this->saveCart([]);
    }

    
    
    public function add(int $id){
   
        
        //  Je retrouve le panier dans la session en tableau 
        //  Si il existe pas , je renvois un tableau vide

        $cart = $this->getCart();

        //  Voir si le produit ($id) existe déjà dans le tableau
        //  si oui, j'augmente la quantité avec [$id]++
        //  sinon ajouter le produit avec la quantité 1

        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        } 

        $cart[$id]++;
        //  je save le tableau mis à jour 

        $this->saveCart($cart);

        // si jamais je veux supprimer le panier je peux utiliser ça
        // $session->remove('cart');

    }

    public function remove(int $id){
        $cart = $this->getCart();

        unset($cart[$id]);
        
        $this->saveCart($cart);
    }

    public function decrement(int $id) {
        $cart = $this->getCart();
        if(!array_key_exists($id, $cart)){
            return;
        }

        if($cart[$id] === 1){
            $this->remove($id);
            return;
        }
        $cart[$id]--;

        $this->saveCart($cart);


    }

     public function getTotal(): int{

        $total = 0;

       foreach ($cart = $this->getCart() as $id => $qty) {
            $product = $this->productRepository->find($id);

           $total += $product->getPrice() * $qty;

        }

        return $total;
     }

     /**
      * @return CartItem[]
      */

    public function getDetailedCartItems(): array {
        $detailedCart = [];
        


        foreach ($cart = $this->getCart() as $id => $qty) {
            $product = $this->productRepository->find($id);

            if (!$product){
                continue;
            }
            $detailedCart[] = new CartItem($product, $qty);

        }
        return $detailedCart;
    }

    
}