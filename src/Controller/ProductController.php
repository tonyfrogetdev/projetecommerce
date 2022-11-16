<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    private $em;
    
    public function __construct(EntityManagerInterface $entityManager){
        $this->em = $entityManager ;
    
    }


    /**
     * @Route("/{slug}", name="product_category", priority = -1)
     */
    public function category($slug, CategoryRepository $categoryRepository): Response
    {

        $category = $categoryRepository->findOneBy([
            'slug' => $slug
        ]);

      

        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{category_slug}/{slug}", name="product_show", priority = -1)
     */
    public function show($slug, ProductRepository $productRepository)
    {;


        $product = $productRepository->findOneBy([
            'slug' => $slug
        ]);
       
      
     
        return $this->render('product/show.html.twig', [
            'product' => $product

        ]);
    }

    /**
     * @Route("/admin/product/{id}/edit"), name="modifier_produit")
     * 
     */
    public function edit(ProductRepository $productRepository, Request $request, EntityManagerInterface $em, $categorySlug,$slug)
    {
    
        $product = $productRepository->findOneBy([
            'category_slug' =>$categorySlug,
            'slug' =>$slug
        ]);

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form-> isValid()) {

            $em->flush();

            return $this->redirectToRoute('modifier_produit', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'formView' => $formView,
        ]);
    }



    /**
     * @Route("/admin/product/create", name="product_create")
     */
    public function create(EntityManagerInterface $em, Request $request, SluggerInterface $slugger)
    {

        $product = new Product;

        $form = $this->createForm(ProductType::class, $product);



        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setSlug(strtolower($slugger->slug($product->getName())));

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('product/create.html.twig', [
            'formView' => $formView
        ]);
    }
}
