<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Purchase;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slugger;
    protected $hash;
    
    public function __construct(SluggerInterface $slugger, UserPasswordHasherInterface $hash)
    {

        $this->slugger = $slugger;
        $this->hash = $hash;
    }


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

      

        $admin = new User;

        $hash = $this->hash->hashPassword($admin, "password");

        $admin->setEmail("admin@gmail.com")
            ->setPassword($hash)
            ->setFullName("Admin")
            ->setRoles(['ROLE_ADMIN']);
        
            $manager->persist($admin);

            $users= [];
           
        for($u= 0; $u < 5; $u++){
            $user = new User();
            $hash = $this->hash->hashPassword($user, "password");
            
            $gender = ['m', 'f'];

            $user->setEmail("user$u@gmail.com")
                ->setFullName($faker->name())
                ->setPassword($hash)
                ->setCivilite($faker->title($gender));
                
            $users[] =$user;

            $manager->persist($user);
        }

        
        for ($c = 0; $c < 3; $c++) {
            $category = new Category;
            $category->setName($faker->department())
                ->setSlug(strtolower($this->slugger->slug($category->getName())));

            $manager->persist($category);

            for ($p = 0; $p < mt_rand(15, 20); $p++) {
                $product = new Product;
                $product->setName($faker->productName())
                    ->setPrice($faker->price(4000, 20000))
                    ->setSlug(strtolower($this->slugger->slug($product->getName())))
                    ->setCategory($category)
                    ->setShortDescription($faker->paragraph())
                    ->setMainPicture($faker->imageUrl(400, 400, true));

                $manager->persist($product);
            }

            for($p = 0; $p < mt_rand(20, 40); $p++){
                $purchase = new Purchase;
                $purchase->setFullName($faker->name())
                        ->setAdress($faker->streetAddress())
                        ->setPostalCode($faker->postcode())
                        ->setCity($faker->city)
                        ->setUser($faker->randomElement($users))
                        ->setTotal(mt_rand(2000, 30000));

                if($faker->boolean(90)){
                    $purchase->setStatus(Purchase::STATUS_PAID);
                }

                $manager->persist($purchase);
            }

            $manager->flush();
        }
    }
}
