<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Product')
            ->setEntityLabelInPlural('Products')
            ->setSearchFields(['title', 'slug', 'description', 'price'])
            ->setDefaultSort(['id' => 'ASC'])
            ->setPaginatorPageSize(5);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();
        yield TextField::new('title');
        yield TextField::new('description');
        yield NumberField::new('price');
        yield ImageField::new('image')
            ->setBasePath('uploads/photos')
            ->setUploadDir('public/uploads/photos')
            ->setUploadedFileNamePattern(
                fn (UploadedFile $file): string => sprintf("%s.%s", bin2hex(random_bytes(6)), $file->guessExtension())
            );
    }
}
