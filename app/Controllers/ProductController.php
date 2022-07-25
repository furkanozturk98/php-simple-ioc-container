<?php

namespace App\Controllers;

use App\Services\ProductService;

class ProductController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @return bool|string
     */
    public function index(): bool|string
    {
        return json_encode($this->productService->getProductList());
    }
}