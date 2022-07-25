<?php

namespace App\Services;

use App\Repository\MysqlProductRepository;

class ProductService
{
    public function __construct(public MysqlProductRepository $productRepository)
    {
    }

    public function getProductList(): array
    {
        return $this->productRepository->getProducts();
    }
}