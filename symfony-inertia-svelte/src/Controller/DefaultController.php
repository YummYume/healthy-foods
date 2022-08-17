<?php

namespace App\Controller;

use App\Services\InertiaHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DefaultController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(InertiaHelper $inertiaHelper): Response
    {
        return $inertiaHelper->render('Index');
    }
}
