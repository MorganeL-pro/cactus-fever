<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsController extends AbstractController
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/products", name="products")
     */
    public function index(): Response
    {
        $categories = $this->categoryRepository->findAll();
        $products = $this->productRepository->findAll();
        return $this->render('products/index.html.twig', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/products/{id}", requirements={"id"="\d+"}, methods={"GET"}, name="products_show")
     */
    public function show(int $id): Response
    {
        $product = $this->productRepository->find($id);
        return $this->render('products/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/products/category/{id}", name="products_category_show", methods={"GET"})
     */
    public function showCategory(Category $category): Response
    {
        $products = $category->getProducts();
        $categories = $this->categoryRepository->findAll();
        return $this->render('products/category.html.twig', [
            'category' => $category,
            'categories' => $categories,
            'products' => $products
        ]);
    }
}
