<?php 

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController {

    /**
     * @Route("/product/{slug}", name="product_show")
     */
    public function show(/*string $slug, ProductRepository $productRepository*/ Product $product)
    {
        // $product = $productRepository->findOneBy(['slug' => $slug]);

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
}