<?php


namespace App\Service;


use Psr\Log\LoggerInterface;

class Helper
{
    private $logger;
    private $defaultImagePath;
    /**
     * Helper constructor.
     * @param $logger
     */
    public function __construct(LoggerInterface $logger, $defaultImagePath)
    {
        $this->logger = $logger;
        $this->defaultImagePath = $defaultImagePath;
    }


    public function sayHello() {
       $this->logger->info("hello my default image path is : ".$this->defaultImagePath);
    }
}