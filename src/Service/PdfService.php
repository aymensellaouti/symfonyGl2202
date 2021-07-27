<?php


namespace App\Service;


use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class PdfService
{
    private $dompdf;
    public function __construct(
        private Environment $twig,
        private $defaultUploadPath
    )
    {
        // Instancier votre objet dompdf
        $this->dompdf = new Dompdf();
        //Préparer un objet pour les options
        $pdfOptions = new Options();
        // Vous pouvez définir le font par défaut
        $pdfOptions->set('defaultFont', 'Garamond');
        // Ajouter ces options
        $this->dompdf->setOptions($pdfOptions);
    }
    private function processPDF($template, $params) {
        // Retrieve the HTML generated in our twig file
        $html = $this->twig->render($template, $params);

        // Charger le html dans Dompdf (utiliser TWIG pour générer
        // du html avec la méthode render du Service Enviremenr)
        $this->dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $this->dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $this->dompdf->render();
    }
    public function stream($template = 'email/pdf_attachement.html.twig', $params = [
        'pageTitle'=> 'Attachement',
        'content' => 'My New PDF Content',
        'title' => 'testing DOMPDF'
    ] ) {
        $this->processPDF($template, $params);
        // Output the generated PDF to Browser (force download)
        $this->dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    }

    public function attach($template = 'email/pdf_attachement.html.twig', $params = [
        'pageTitle'=> 'Attachement',
        'content' => 'My New PDF Content',
        'title' => 'testing DOMPDF'
    ] ): array {
        $this->processPDF($template, $params);
        // Store PDF Binary Data
        $output = $this->dompdf->output();

        // e.g /var/www/project/public/mypdf.pdf
//        $unique
        $uniqueId = uniqid();
        $pdfFilepath =  "{$this->defaultUploadPath}/down{$uniqueId}.pdf";
        try {
            file_put_contents($pdfFilepath, $output);
        } catch (\ErrorException $exception) {
            return new \Exception('Problème dans la sauvgarde du fichier');
        }
        // Write file to the desired path

        // Send some text response
        return [
            'path' => $pdfFilepath,
            'data' => $output
        ];
    }
}