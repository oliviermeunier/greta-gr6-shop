<?php 

namespace App\Controller;

use App\Entity\Report;
use App\Entity\Review;
use DateTimeImmutable;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\ReviewType;
use App\Repository\ReportRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController {

    /**
     * @Route("/product/{slug}", name="product_show")
     */
    public function show(Product $product, Request $request, EntityManagerInterface $manager, ReviewRepository $reviewRepository)
    {
        $user = $this->getUser(); // On récupère l'utilisateur connecté
        $review = new Review(); // On crée un objet Review qui contiendra l'éventuel nouvel avis si l'internaute remplit le formulaire
       
        if ($user) {
            $review->setNickname($user->getFullName());
        } 

        $reviewForm = $this->createForm(ReviewType::class, $review); // On crée le formulaire en lui associant notre objet $review
        $reviewForm->handleRequest($request); // On transmet les données de la requête au formulaire

        if ($reviewForm->isSubmitted() && $reviewForm->isValid()) { // Si le formulaire est soumis ET valide... 

            // $review contient déjà les 3 champs du formulaire : nickname, content et grade
            $review->setCreatedAt(new DateTimeImmutable()); // on le complète avec la date du jour
            $review->setProduct($product); // et le produit associé
            $review->setUser($user); // On associe l'utilisateur connecté

            // On l'enrgistre ensuite en base de données
            $manager->persist($review);
            $manager->flush();

            // Message flash et redirection
            $this->addFlash('success', 'Votre avis a bien été pris en compte');
            return $this->redirectToRoute('product_show', ['slug' => $product->getSlug()]);
        }

        // Sélection des avis associés au produit qui ont moins de 5 signalements
        $validReviews = $reviewRepository->findValidReviews($product);

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'reviewForm' => $reviewForm->createView(),
            'validReviews' => $validReviews
        ]);
    }

    /**
     * @Route("/category/{slug}", name="product_category")
     */
    public function category(Category $category)
    {
        return $this->render('product/category.html.twig', [
            'category' => $category
        ]);
    }

    /**
     * @Route("/product/{slug}/review/{id}/reports", name="product_review_reports")
     */
    public function reports(Review $review, string $slug, EntityManagerInterface $manager)
    {
        // On récupère l'utilisateur connecté
        $user = $this->getUser();

        // Si l'utilisateur n'a pas déjà signalé cet avis... il peut le faire
        if ($user->canReport($review)) {

            $report = new Report();
            $report->setUser($this->getUser());
            $report->setReview($review);
            $report->setCreatedAt(new DateTimeImmutable);
    
            $manager->persist($report);
            $manager->flush();
    
            $this->addFlash('success', 'Avis signalé');
        }
        else {

            // Sinon on l'avertit qu'il l'a déjà fait ou bien il est l'auteur de l'avis
            $this->addFlash('warning', 'Vous ne pouvez pas signaler cet avis');
        }
        
        // On redirige vers la page produit
        return $this->redirectToRoute('product_show', ['slug' => $slug]);
    }

    /**
     * @Route("/product/{slug}/review/{id}/unreports", name="product_review_unreports")
     */
    public function unreports(Review $review, string $slug, EntityManagerInterface $manager, ReportRepository $reportRepository)
    {
        // On récupère l'utilisateur connecté
        $user = $this->getUser();

        // Si l'utilisateur peut supprimer son signalement de l'avis... 
        if ($user->canUnreport($review)) {

            // On récupère le signalement associé à l'utilisateur connecté et à l'avis récupéré en paramètre
            $report = $reportRepository->findOneBy([
                'user' => $this->getUser(),
                'review' => $review
            ]);

            // ... on le supprime !
            $manager->remove($report);
            $manager->flush();

            $this->addFlash('success', 'Signalement supprimé');
        }
        else {

            // Sinon on l'avertit qu'il l'a déjà fait ou bien il est l'auteur de l'avis
            $this->addFlash('warning', 'Vous ne pouvez pas supprimer ce signalement');
        }
        
        // On redirige vers la page produit
        return $this->redirectToRoute('product_show', ['slug' => $slug]);
    }

    /**
     * @Route("/product/{slug}/review/{id<\d+>}/delete", name="product_review_delete")
     * @Security("isGranted(constant('App\\Security\\Voter\\ReviewVoter::DELETE'),review"))
     */
    public function deleteReview(string $slug, Review $review, EntityManagerInterface $manager)
    {
        $manager->remove($review);
        $manager->flush();

        $this->addFlash('success', 'Avis supprimé');
        return $this->redirectToRoute('product_show', ['slug' => $slug]);
    }
}