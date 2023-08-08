<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Order')
            ->setEntityLabelInPlural('Orders')
            ->setSearchFields(['createdBy', 'status', 'totalPrice', 'createdAt'])
            ->setDefaultSort(['id' => 'ASC'])
            ->setPaginatorPageSize(5);
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();
        yield TextField::new('createdBy')
            ->hideOnForm();
        yield TextField::new('status');
        yield MoneyField::new('totalPrice')
            ->hideOnForm()
            ->setCurrency('EUR');
        yield DateField::new('createdAt')
            ->hideOnForm();
        
    }
}
