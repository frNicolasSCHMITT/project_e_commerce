<?php

namespace App\Service\Cart;

use Symfony\Component\HttpFoundation\RequestStack;

class CartService {

  protected $session;

  public function __construct(RequestStack $RequestStack) {
    
    $this->session = $RequestStack->getSession();

  }

  public function add(int $id) {
        
    $cart = $this->session->get('cart', []);

    if(!empty($cart[$id])){
        $cart[$id]++;
    } else {
        $cart[$id] = 1;
    }

    $this->session->set('cart', $cart);
  }

  public function remove(int $id) {
        
    $cart = $this->session->get('cart', []);

    if($cart[$id] > 1){
        $cart[$id]--;
    } else {
      unset($cart[$id]);
    }

    $this->session->set('cart', $cart);
  }

  public function delete(int $id) {
    
    $cart = $this->session->get('cart', []);

    if(!empty($cart[$id])){
        unset($cart[$id]);
    }

    $this->session->set('cart', $cart);
  }

  public function getFullCart() : array {}

  public function getTotal() : float {}
}