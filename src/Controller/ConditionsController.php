<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConditionsController extends AbstractController
{
    /**
     * @Route("/termes-et-conditions", name="conditions")
     */
    public function index(Request $request)
    {


        return $this->render('conditions/index.html.twig');
    }
}
