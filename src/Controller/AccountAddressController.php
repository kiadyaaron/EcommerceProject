<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager  = $entityManager;
    }
    #[Route('/compte/address', name: 'app_account_address')]
    public function index(): Response
    {
        
        return $this->render('account/Address.html.twig');
    }

    #[Route('/compte/ajouter-une-adresse', name: 'account_address_add')]
    public function add(Cart $cart, Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            if($cart->getfull()){
                return $this->redirectToRoute('app_order');
            }else{
                return $this->redirectToRoute('app_account_address');
            }
        }
        return $this->render('account/Address_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/compte/edit_address/{id}', name: 'app_address_edit')]
    public function edit(Request $request,$id): Response
    {   
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account_address');
        }
        $form = $this->createForm(AddressType::class, $address);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $this->entityManager->flush();
            return $this->redirectToRoute('app_account_address');
        }

        return $this->render('account/address_add.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    #[Route('/compte/delete_address/{id}', name: 'app_address_delete')]
    public function delete($id): Response
    {   
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if ($address || $address->getUser() != $this->getUser()) {
            $this->entityManager->remove($address);
            $this->entityManager->flush();
        }
        
            return $this->redirectToRoute('app_account_address');
        
    }
}
