<?php

namespace App\Repository;

class MysqlProductRepository
{
    public function getProducts()
    {
        return [
            [
                "id" => 1,
                "title" => "product 1",
                "price" => "$120"
            ],
            [
                "id" => 2,
                "title" => "product 2",
                "price" => "$300"
            ],
            [
                "id" => 3,
                "title" => "product 3",
                "price" => "$1000"
            ]
        ];
    }
}