<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\ArticleRepository;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="app_cart")
     */
    public function index(RequestStack $RequestStack, ArticleRepository $ArticleRepository): Response
    {
        $session = $RequestStack->getSession();

        $cart = $session->get('cart', []);

        $cartData = [];

        foreach($cart as $id => $quantity) {
            $cartData[] = [
                'product' => $ArticleRepository->find($id),
                'quantity' => $quantity
            ];
        }

        $total = 0;

        foreach($cartData as $item){
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }

        return $this->render('cart/index.html.twig', [
            'items' => $cartData,
            'total' => $total
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add($id, RequestStack $RequestStack){
        $session = $RequestStack->getSession();
        
        $cart = $session->get('cart', []);

        if(!empty($cart[$id])){
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute("app_cart");
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove($id, RequestStack $RequestStack){
        $session = $RequestStack->getSession();

        $cart = $session->get('cart', []);

        if(!empty($cart[$id])){
            unset($cart[$id]);
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute("app_cart");
    }
}
