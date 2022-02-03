<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'article'
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Contenu de l\'article'
            ])
            // ->add('product', EntityType::class, [
            //     'choice_label' => 'name',
            //     'class' => Category::class,
            //     'label' => 'Produit'
            // ])
            // ->add('category', EntityType::class, [
            //     'choice_label' => 'name',
            //     'class' => Category::class,
            //     'label' => 'Catégorie'
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
