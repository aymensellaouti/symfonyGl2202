<?php


namespace App\EventListener;

use App\Events\AddProductEvents;
use Psr\Log\LoggerInterface;

class AddProductEventListener
{
    public function __construct(private LoggerInterface $logger)
    {}
    public function onProductAdded(AddProductEvents $event) {
        $this->logger->info("in AddProductEventListener j'écoute l'event 
                            addProduct event avec le name {$event->getProduct()}");
    }
}