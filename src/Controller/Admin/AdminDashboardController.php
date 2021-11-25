<?php

namespace App\Controller\Admin;

use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController {

    /**
     * @Route("/admin", name="admin_dashboard_index")
     */
    public function index(ProductRepository $productRepository)
    {
        $products = $productRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/dashboard/index.html.twig', [
            'products' => $products 
        ]);
    }

}