<?php

namespace App\Controller\Admin;

use App\Entity\BankTransactions;
use App\Entity\Config;
use App\Entity\ForumReplies;
use App\Entity\Forums;
use App\Entity\ForumThreads;
use App\Entity\Messages;
use App\Entity\Transactions;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
        return $this->render('bundles/EasyAdminBundle/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Equuinity');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::linkToUrl('Back to Equuinity', 'fa fa-horse-head', '/'),

            MenuItem::section('Site Config'),
            MenuItem::linkToCrud('Config', 'fa fa-cogs', Config::class),

            MenuItem::section('Users'),
            MenuItem::linkToCrud('Users', 'fa fa-user', User::class),
            MenuItem::linkToCrud('Bank', 'fa fa-money', BankTransactions::class),
            MenuItem::linkToCrud('Credits', 'fa fa-heart', Transactions::class),

            MenuItem::section('Forums'),
            MenuItem::linkToCrud('Forums', 'fa fa-book', Forums::class),
            MenuItem::linkToCrud('Forum Threads', 'fa fa-book', ForumThreads::class),
            MenuItem::linkToCrud('Forum Replies', 'fa fa-book', ForumReplies::class),

            MenuItem::section('Messages'),
            MenuItem::linkToCrud('Messages', 'fa fa-envelope', Messages::class),
        ];
    }
}
