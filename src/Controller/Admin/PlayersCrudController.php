<?php

namespace App\Controller\Admin;

use App\Entity\Players;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PlayersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Players::class;
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
