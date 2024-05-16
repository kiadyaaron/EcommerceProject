<?php

namespace App\Classe;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart 
{
    private RequestStack $requestStack;
    private ProductRepository $productRepository;

    public function __construct(RequestStack $requestStack, ProductRepository $productRepository)
    {
        $this->requestStack = $requestStack;
        $this->productRepository = $productRepository;
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }

    public function add($id)
    {
        $cart = $this->getSession()->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->getSession()->set('cart', $cart);
    }

    public function remove()
    {
        $this->getSession()->remove('cart');
    }

    public function delete($id)
    {
        $cart = $this->getSession()->get('cart', []);
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        $this->getSession()->set('cart', $cart);
    }

    public function decrease($id)
    {
        $cart = $this->getSession()->get('cart', []);
        if (!empty($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }
        $this->getSession()->set('cart', $cart);
    }

    public function getFull()
    {
        $cart = $this->getSession()->get('cart', []);
        $fullCart = [];

        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->find($id);
            if ($product) {
                $fullCart[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }

        return $fullCart;
    }
}
