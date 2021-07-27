<?php

namespace App\EventSubscriber;

use App\Events\AddProductEvents;
use App\Service\MailerService;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private MailerService $mailer
    ){}

    public function onProductAdded(AddProductEvents $event)
    {
        $product = $event->getProduct();
        $this->mailer->sendEmail();
        $this->logger->info("Product {$product->getName()} has been added");
    }

    public static function getSubscribedEvents()
    {
        return [
            'product.added' => 'onProductAdded',
        ];
    }
}
