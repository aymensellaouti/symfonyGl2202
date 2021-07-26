<?php


namespace App\Events;


use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class AddProductEvents extends Event
{
    const ADD_PRODUCT_EVENT = "product.added";

    public function __construct(private Product $product)
    {}

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }
}