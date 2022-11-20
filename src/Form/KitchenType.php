<?php

namespace App\Form;

use App\Entity\Kitchen;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class KitchenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /*$kitchen = $options['data'] ?? null;
        $member = $kitchen->getOwner();
        $id = $member->getId();*/


        $builder
            ->add('name')
            ->add('published')
            ->add('book')
            ->add('Owner', null, [
                'disabled'   => true,
            ])
            /*->add('book', CookbookType::class, [
                'class' => Member::class,
                'choices' => $group->getMember()->findBy(['id' => $id])
                ]
            )


            ->add('book', CookbookType::class, [
                'class' => Member::class,
                'query_builder' => function (CookbookRepository $er) use ($member) {
                    return $er->createQueryBuilder('g')
                        ->leftJoin('g.bookshelf', 'i')
                        ->andWhere('i.owner = :member')
                        ->setParameter('member', $member)
                        ;
                }
            ])*/
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Kitchen::class,
        ]);
    }
}
