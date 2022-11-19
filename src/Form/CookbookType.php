<?php

namespace App\Form;

use App\Entity\Cookbook;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CookbookType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('title')
            ->add('author')
            ->add('cuisine')
            ->add('year')
            ->add('shelf')
            ->add('cuisinetype')
            //->add('kitchens')
            ->add('kitchens', null, [
                'disabled'   => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cookbook::class,
        ]);
    }
}
