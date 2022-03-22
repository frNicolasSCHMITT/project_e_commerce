<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\ArticleRepository;
use App\Service\Cart\CartService;

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
    public function add($id, CartService $cartService){
        $cartService->add($id);

        return $this->redirectToRoute("app_cart");
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove($id, CartService $cartService){
        $cartService->remove($id);

        return $this->redirectToRoute("app_cart");
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete")
     */
    public function delete($id, CartService $cartService){
        $cartService->delete($id);

        return $this->redirectToRoute("app_cart");
    }
}
