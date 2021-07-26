<?php

namespace App\EventSubscriber;

use App\Events\AddProductEvents;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger
    ){}

    public function onProductAdded(AddProductEvents $event)
    {
        $product = $event->getProduct();
        $this->logger->info("Product {$product->getName()} has been added");
    }

    public static function getSubscribedEvents()
    {
        return [
            'product.added' => 'onProductAdded',
        ];
    }
}
