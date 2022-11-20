<?php

namespace App\Form;

use App\Entity\Kitchen;
use App\Entity\Cookbook;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Doctrine\Common\Collections\ArrayCollection;

class KitchenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $kitchen = $options['data'] ?? null;
        $member = $kitchen->getOwner();
        $books = $member->getCookbooks();


        $builder
            ->add('name')
            ->add('published')
            ->add('book')
            ->add('Owner', null, [
                'disabled'   => true,
            ])

        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Kitchen::class,
        ]);
    }
}
