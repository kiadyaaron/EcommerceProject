<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;

class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/order', name: 'app_order')]
    public function index(Cart $cart, Request $request): Response
    {
        if (!$this->getUser()->getAddresses()->getValues()) 
        {
            return $this->redirectToRoute('account_address_add');
        }

        $form = $this->createForm(OrderType::class,null,[
            'user' => $this->getUser()
        ]);

        return $this->render('order/index.html.twig',[
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }


    #[Route('/order/recap', name: 'app_order_recap', methods:['POST'])]
    public function add(Cart $cart, Request $request): Response
    {
        $form = $this->createForm(OrderType::class,null,[
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $date = new \DateTimeImmutable();
            $carriers = $form->get('carriers')->getData();
            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstname();
            $delivery_content .= '<br>'.$delivery->getPhone();

            
            $delivery_content .= '<br>'.$delivery->getAddress();
            $delivery_content .= '<br>'.$delivery->getPostal().' '.$delivery->getCity();
            
            
            // Enregister mes commandes Order()
            $order = new Order();
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);

            $this->entityManager->persist($order);

            // Enregister mes produits OrderDetails()
            foreach ($cart->getFull() as $product) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                $this->entityManager->persist($orderDetails);
            }

            //dd($order);
            $this->entityManager->flush();

           

            return $this->render('order/add.html.twig',[
                'cart' => $cart->getFull(),
                'carrier' => $carriers,
                'delivery' => $delivery_content,
                'reference' => $order->getReference()
            ]);
        }
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/order/recap', name: 'app_order_recap_get', methods: ['GET'])]
    public function showForm(): Response
    {
        return $this->redirectToRoute('app_order');
    }
}