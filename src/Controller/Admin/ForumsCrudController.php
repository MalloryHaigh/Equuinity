<?php

namespace App\Controller\Admin;

use App\Entity\Forums;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ForumsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Forums::class;
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
