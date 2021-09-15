<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Classe\Mail;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande/merci/{stripeSessionId}', name: 'order_validate')]
    public function index(Cart $cart, $stripeSessionId): Response
    {
    $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

    if (!$order || $order->getUser() != $this->getUser()){
        return $this->redirectToRoute('home');
    }

    if($order->getState() == 0){
    // vider la session "cart"
    $cart->remove();
    // Modifier le statut isPaid de notre commande en mettant 1
    $order->setState(1);
    $this->entityManager->flush();

    $mail = new Mail();
    $content = "Bonjour ".$order->getUser()->getFirstname()."<br/>Merci pour votre commande.<br><br>Voici les détails de votre commande :<br> 
   <b> Référence de ma commande : <b/>".$order->getReference()."<br> 
   <b> Votre colis vous sera livrée par <b/>".$order->getCarrierName()."";
    $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Confirmation de commande Karasuno.', $content);
        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
