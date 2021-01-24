<?php

namespace App\Controller\Admin;

use App\Entity\ForumReplies;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ForumReplies2CrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ForumReplies::class;
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
