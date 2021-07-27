<?php


namespace App\Controller;


use App\Service\PdfService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController
{
    /**
     * @Route("", name="home")
     */
    public function index() {
        return $this->redirectToRoute('first');
    }
    /**
     * @Route("/pdf", name="first")
     */
    public function first(PdfService $pdf) {
        $pdf->attach();
        return new Response("<html><body><h1>Hello Gl2 2021</h1></body></html>");
    }

}