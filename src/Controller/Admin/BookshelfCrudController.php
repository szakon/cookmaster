<?php

namespace App\Controller\Admin;

use App\Entity\Bookshelf;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BookshelfCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Bookshelf::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
