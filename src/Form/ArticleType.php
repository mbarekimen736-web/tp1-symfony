<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de l\'article',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Saisissez le titre'
                ]
            ])

            ->add('contenu', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 6
                ]
            ])

            ->add('auteur', TextType::class, [
                'label' => 'Auteur',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom auteur'
                ]
            ])

            ->add('dateCreation', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date création',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

            ->add('publie', CheckboxType::class, [
                'label' => 'Publier maintenant',
                'required' => false
            ])

            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'placeholder' => '-- Choisir catégorie --',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

            ->add('enregistrer', SubmitType::class, [
                'label' => '💾 Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary w-100 mt-3'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}