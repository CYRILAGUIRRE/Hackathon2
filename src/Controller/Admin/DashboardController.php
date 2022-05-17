<?php

namespace App\Controller\Admin;

use App\Entity\Club;
use App\Entity\Players;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
	public function __construct(
		private AdminUrlGenerator $adminUrlGenerator
	){
	}
	#[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
	        ->setController(UserCrudController::class)
	        ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sports Manager');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
		yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-list', User::class);
		yield MenuItem::linkToCrud('Clubs', 'fas fa-list', Club::class);
		yield MenuItem::linkToCrud('Joueurs', 'fas fa-list', Players::class);
    }

}
