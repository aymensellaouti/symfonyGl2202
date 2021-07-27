<?php

namespace App\EventSubscriber;

use App\Events\AddProductEvents;
use App\Service\MailerService;
use App\Service\PdfService;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private MailerService $mailer,
        private PdfService $pdf
    ){}

    public function onProductAdded(AddProductEvents $event)
    {
        $product = $event->getProduct();

        $uploadData = $this->pdf->attach();
        $this->mailer->sendEmail('aymen.sellaouti@gmail.com',"Hello World", 'text/html', $uploadData['data']);
        $this->logger->info("Product {$product->getName()} has been added");
    }

    public static function getSubscribedEvents()
    {
        return [
            'product.added' => 'onProductAdded',
        ];
    }
}
