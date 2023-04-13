<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\Etablissement;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;


class DashboardController extends AbstractDashboardController
{

    public function __construct( private AdminUrlGenerator $adminUrlGenerator)
    {

    }
    #[Route('/admin', name: 'admin')]
    public function indexAdmin(Security $security): Response
    {
        $user = $security->getUser()->getRoles();
        if (in_array('ROLE_ADMIN', $user)) {
            $url = $this->adminUrlGenerator
                ->setController(EtablissementCrudController::class)->generateUrl();
            return  $this->redirect($url);

        } else {
           return $this->redirectToRoute('app_acceuil');
        }
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('FC-Tourisme Dashboard');
    }

    public function configureMenuItems(): iterable
    {

        yield MenuItem::section('Etablissements');

        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Créer un Etablissement', 'fas fa-plus', Etablissement::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir les Etablissements', 'fas fa-eye', Etablissement::class)
        ]);

        yield MenuItem::section('Utilisateurs');

        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Créer un Utilisateur', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir les Utilisateurs', 'fas fa-eye', User::class)
        ]);

        yield MenuItem::section('Categories');

        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Créer une Categorie', 'fas fa-plus', Categorie::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir les Categories', 'fas fa-eye', Categorie::class)
        ]);
    }
}
