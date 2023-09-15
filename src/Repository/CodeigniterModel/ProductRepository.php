<?php

namespace App\Repository\CodeigniterModel;

use App\Domain\Product;
use MY_Model;

class ProductRepository extends MY_Model
{
    /**
     * 
     * @return Product[] 
     */
    public function findAll(): array
    {
        return $this->qb()->get_where('product')->result(Product::class);
    }
}
