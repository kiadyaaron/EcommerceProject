<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart 
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function add($id)
    {
        $panier = $this->session->get('panier', []);
        $panier[] = [
            'id' => $id,
            'quantity' => 1
        ];
        
    }
}
