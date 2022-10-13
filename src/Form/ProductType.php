<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom du produit',
            'attr' => ['class'=> 'form-control', 'placeholder' => 'Tapez le nom du produit'],
            'required'=>false,
            
        ])
        ->add('shortDescription', TextareaType::class, [
            'label'=> 'Description courte',
            'attr'=> [
                'class'=>'form-control',
                'placeholder'=> 'Tapez une description assez courte mais parlante pour le visiteur'],
                'required'=>false,
        ])
        ->add('price', MoneyType::class, [
            'label' => 'Prix du produit',
            'attr' => [
                'class'=>'form-control',
                'placeholder'=>'Indiquez le prix du produit en €'
            ],
            'divisor'=>100,
            'required'=>false,
            
            ])


        ->add('mainPicture', UrlType::class, [
            'label' => 'Image du produit',
            'attr' => ['placeholder' => 'tapez une url d\'image !'],
            'required'=>false,
            
        ])
        ->add('category', EntityType::class, [
            'label'=>'Catégorie',
            'attr'=>[
                'class'=>'form-control'],
                'placeholder'=> '-- Choisir une catégorie --',
                'class' => Category::class,
                'choice_label'=>function(Category $category){
                    return strtoupper($category->getName());
                }
            
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
