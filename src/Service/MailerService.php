<?php


namespace App\Service;


class MailerService
{

    public function __construct(private \Swift_Mailer $mailer)
    {
    }

    public function sendEmail() {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('aymen.noreply@gmail.com')
            ->setTo('aymen.sellaouti@gmail.com')
            ->setBody(
                "cc je suis un email de l'appli Symfo :)"
//                $this->renderView(
//                // templates/hello/email.txt.twig
//                    'hello/email.txt.twig',
//                    ['name' => $name]
//                )
            )
        ;
        $this->mailer->send($message);
    }

}