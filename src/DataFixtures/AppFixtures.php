<?php

namespace App\DataFixtures;

use App\Avatar\AvatarHelper;
use Faker\Factory;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Product;
use Liior\Faker\Prices;
use App\Entity\Category;
use Cocur\Slugify\Slugify;
use App\Avatar\AvatarSvgFactory;
use Bezhanov\Faker\Provider\Commerce;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $slugger;
    private $hasher;
    private $avatarHelper;
    private $avatarSvgFactory;

    public function __construct(
        Slugify $slugger, 
        UserPasswordHasherInterface $hasher, 
        AvatarHelper $avatarHelper,
        AvatarSvgFactory $avatarSvgFactory)
    {
        $this->avatarHelper = $avatarHelper;
        $this->hasher = $hasher;
        $this->slugger = $slugger;
        $this->avatarSvgFactory = $avatarSvgFactory;
    }

    public function load(ObjectManager $manager): void
    {
        //On supprime le dossier avatars
        $this->avatarHelper->removeAvatarFolder();

        // Création de l'objet Faker
        $faker = Factory::create();
        $faker->addProvider(new Commerce($faker));
        $faker->addProvider(new PicsumPhotosProvider($faker));
        $faker->addProvider(new Prices($faker));

        //////////////
        // CATEGORY //
        //////////////

        // Création des catégories
        $categoryNames = [
            'Hommes',
            'Femmes',
            'Enfants',
            'Soldes'
        ];

        // On stocke nos catégories dans un tableau pour les retrouver plus tard
        $categories = [];

        foreach ($categoryNames as $categoryName) {

            // Création d'un objet (entité) Category
            $category = new Category();
            $category->setName($categoryName);
    
            $createdAt = $faker->dateTimeBetween('-5 years', 'now');
            $category->setCreatedAt(DateTimeImmutable::createFromMutable($createdAt));

            $slug = $this->slugger->slugify($category->getName());
            $category->setSlug($slug);
    
            // On stocke notre entité Category dans le tableau d'objets Category
            $categories[] = $category;

            // On le persiste en BDD avec l'entity manager
            $manager->persist($category);
        }

        /////////////
        // PRODUCT //
        /////////////
        for ($i=0; $i < 20; $i++) {

            $product = new Product();
            $product->setName($faker->productName);

            $createdAt = $faker->dateTimeBetween('-5 years', 'now');
            $product->setCreatedAt(DateTimeImmutable::createFromMutable($createdAt));

            $product->setCategory($faker->randomElement($categories));
            $product->setPrice($faker->price(500,50000, true));
            $product->setDescription($faker->text(2500));
            $product->setThumbnail($faker->imageUrl(600,300, true));

            $slug = $this->slugger->slugify($product->getName());
            $product->setSlug($slug);

            $manager->persist($product);
        }

        /////////////
        // USER //
        /////////////
        for ($i=1; $i <= 10; $i++) {

            $user = new User();

            if ($i == 1) {
                $user->setEmail("admin@gmail.com");
                $user->setRoles(['ROLE_ADMIN']);
            }
            else {
                $user->setEmail("user$i@gmail.com");
            }
            
            $user->setPassword($this->hasher->hashPassword($user, 'password'));
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $createdAt = $faker->dateTimeBetween('-5 years', 'now');
            $user->setCreatedAt(DateTimeImmutable::createFromMutable($createdAt));

            // Avatar
            $size = AvatarSvgFactory::DEFAULT_SIZE;
            $nbColors = AvatarSvgFactory::DEFAULT_NB_COLORS;
            $svg = $this->avatarSvgFactory->createRandomAvatar($size, $nbColors);
            
            $filename = $this->avatarHelper->save($svg);
            $user->setAvatar($filename);

            $manager->persist($user);
        }

        // On demande au manager d'exécuter toutes les requêtes SQL
        $manager->flush();
    }
}
