<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use Cocur\Slugify\Slugify;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminProductController extends AbstractController {

    private $manager;
    private $slugger;

    public function __construct(Slugify $slugger, EntityManagerInterface $manager)
    {
        $this->slugger = $slugger;
        $this->manager = $manager;
    }

    /**
     * @Route("/admin/product/add", name="admin_product_add")
     */
    public function add(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setCreatedAt(new DateTimeImmutable());
            
            $slug = $this->slugger->slugify($product->getName());
            $product->setSlug($slug);

            $this->manager->persist($product);
            $this->manager->flush();

            $this->addFlash('success', 'Produit ajouté');
            return $this->redirectToRoute('admin_dashboard_index');
        }

        return $this->render('admin/product/add.html.twig', [
            'form' => $form->createView()
        ]);
    }   

    /**
     * @Route("/admin/product/edit/{id<\d+>}", name="admin_product_edit")
     */
    public function edit()
    {
        dd('FORM EDIT PRODUIT');
    } 

    /**
     * @Route("/admin/product/delete/{id<\d+>}", name="admin_product_delete")
     */
    public function delete()
    {
        dd('SUPPR PRODUIT');
    } 
}