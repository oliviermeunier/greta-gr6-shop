<?php 

namespace App\Controller;

use App\Entity\Review;
use DateTimeImmutable;
use App\Entity\Product;
use App\Form\ReviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController {

    /**
     * @Route("/product/{slug}", name="product_show")
     */
    public function show(Product $product, Request $request, EntityManagerInterface $manager)
    {
        $review = new Review(); // On crée un objet Review qui contiendra l'éventuel nouvel avis si l'internaute remplit le formulaire
        $reviewForm = $this->createForm(ReviewType::class, $review); // On crée le formulaire en lui associant notre objet $review
        $reviewForm->handleRequest($request); // On transmet les données de la requête au formulaire

        if ($reviewForm->isSubmitted()) { // Si le formulaire est soumis... 

            // $review contient déjà les 3 champs du formulaire : nickname, content et grade
            $review->setCreatedAt(new DateTimeImmutable()); // on le complète avec la date du jour
            $review->setProduct($product); // et le produit associé

            // On l'enrgistre ensuite en base de données
            $manager->persist($review);
            $manager->flush();

            // Message flash et redirection
            $this->addFlash('success', 'Votre avis a bien été pris en compte');
            return $this->redirectToRoute('product_show', ['slug' => $product->getSlug()]);
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'reviewForm' => $reviewForm->createView()
        ]);
    }
}