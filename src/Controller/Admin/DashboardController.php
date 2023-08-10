<?php

namespace App\Controller\Admin;

use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\CountryRepository;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private ChartBuilderInterface $chartBuilder,
        private ProductRepository $productRepository,
        private OrderRepository $orderRepository,
        private CustomerRepository $customerRepository,
        private CountryRepository $countryRepository
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $chart = $this->createChart();
        $paidOrders = $this->orderRepository->findBy(['status' => 'paid']);
        $totalIncome = array_reduce($paidOrders, fn ($carry, $order) => $carry + $order->getTotalPrice(), 0);

        return $this->render('admin/dashboard.html.twig', [
            'chart' => $chart,
            'customerCount' => count($this->customerRepository->findAll()),
            'productsCount' => count($this->productRepository->findAll()),
            'paidOrders' => count($paidOrders),
            'recentCustomers' => $this->customerRepository->getRecentCustomers(),
            'recentOrders' => $this->orderRepository->getRecentOrders(),
            'totalIncome' => $totalIncome
        ]);
    }

    private function createChart(): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $countries = $this->countryRepository->findAll();
        $colors = array_map(fn() => 'rgb(' . rand(0, 255) . ',' . rand(0, 255) . ',' . rand(0, 255) . ')', $countries);
        $labels = array_map(fn($country) => $country->getName(), $countries);
        $data = array_map(fn($country) => $country->getOrderCount(), $countries);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Orders by Country',
                    'backgroundColor' => $colors,
                    'borderColor' => $colors,
                    'data' => $data,
                ],
            ],
        ]);
        return $chart;
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addWebpackEncoreEntry('app');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('E Commerce');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Products', 'fas fa-list', Product::class);
        yield MenuItem::linkToCrud('Orders', 'fas fa-list', Order::class);
        yield MenuItem::linkToCrud('Users', 'fa-solid fa-user-group', User::class);
        yield MenuItem::linkToCrud('Customers', 'fa-solid fa-users', Customer::class);
    }
}
