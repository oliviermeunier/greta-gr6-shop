<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController {

    /**
     * @Route("/admin", name="admin_dashboard_index")
     */
    public function index()
    {
        return $this->render('admin/dashboard/index.html.twig');
    }

}