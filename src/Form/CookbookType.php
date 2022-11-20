<?php

namespace App\Form;

use App\Entity\Cookbook;
use App\Entity\Member;
use App\Entity\Bookshelf;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CookbookType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $cookbook = $options['data'] ?? null;
        $member = $cookbook->getMember();
        $bookshelves = $member->getbookshelf();

        $builder
            ->add('title')
            ->add('author')
            ->add('cuisine')
            ->add('year')
            ->add('shelf', EntityType::class, [
                'class' => Bookshelf::class,
                'choices' => $bookshelves,
                'choice_label' => 'shelf'])
            ->add('cuisinetype')
            //->add('kitchens')
            ->add('kitchens', null, [
                'disabled'   => true,
            ])
            ->add('member', null, [
                'disabled' => true,
                ])
            /*->add('member' => $member, null, [
                'disabled'   => true,
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cookbook::class,
        ]);
    }
}
