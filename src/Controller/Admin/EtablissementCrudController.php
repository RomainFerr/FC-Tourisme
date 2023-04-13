<?php

namespace App\Controller\Admin;

use App\Entity\Etablissement;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EtablissementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Etablissement::class;
    }
}