<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/product", name="product")
     */
    public function index(): Response
    {
        $categories = $this->categoryRepository->findAll();
        $products = $this->productRepository->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/product/{id}", requirements={"id"="\d+"}, methods={"GET"}, name="product_show")
     */
    public function show(int $id): Response
    {
        $product = $this->productRepository->find($id);
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
