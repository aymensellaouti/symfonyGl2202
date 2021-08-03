<?php


namespace App\Service;


class MailerService
{

    public function __construct(
        private \Swift_Mailer $mailer,
        private PdfService $pdf
    )
    {}

//    public function sendEmail() {
//        $message = (new \Swift_Message('Hello Email'))
//            ->setFrom('aymen.noreply@gmail.com')
//            ->setTo('aymen.sellaouti@gmail.com')
//            ->setBody(
//                "cc je suis un email de l'appli Symfo :)"
////                $this->renderView(
////                // templates/hello/email.txt.twig
////                    'hello/email.txt.twig',
////                    ['name' => $name]
////                )
//            )
//        ;
//        $this->mailer->send($message);
//    }


    /**
     * @param $to
     * @param $template
     * @param string $contentType
     * @param null $attachement
     */
    public function sendEmail($to, $template, $contentType='text/html', $attachement = null) {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('aymen.noreply@gmail.com')
            ->setTo($to)
            ->setBody(
                $template,
                $contentType
            );
        if ($attachement) {
            $attachementObject = new \Swift_Attachment($attachement,
                'attachement.pdf',
                'application/pdf' );
            $message->attach($attachementObject);
        }

        $this->mailer->send($message);
    }
}