<?php

namespace MicroBundle\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MicroBundle\Entity\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("document")
 */
class PdfController extends Controller
{


    /**
     * Print to pdf Document
     *
     * @Route("/pdf/{document}", name="document_pdf")
     * @param Document $document
     * @return PdfResponse
     */
    public function pdfDocumentAction(Document $document)
    {
        $prepareService = $this->get('prepareHtmlToPdf');
        $html = $prepareService->getContent($document);
        $header = $prepareService->getHeader($document);
        $footer = $prepareService->getFooter($document);

        $marginBottom = ($document->getPdfSettings()->getShowStamp() || !empty($document->getPdfSettings()->getInspectors()))
            ? '45mm' : '25mm';

        $options = [

            'images' => true,
            'enable-javascript' => true,
            'page-size' => 'A4',
            'viewport-size' => '1280x1024',
            'header-html' => $header,
            'footer-html' => $footer,
            'margin-left' => '10mm',
            'margin-right' => '10mm',
            'margin-top' => '33mm',
            'margin-bottom' => $marginBottom,
            'header-spacing'=> '3',

        ];
        $snappy =  $this->get('knp_snappy.pdf');


        $snappy->setOption('header-html', $header);
        $snappy->setOption('footer-html', $footer);
        $snappy->setTimeout(40);
        $filename = "Document_nr_" . $document->getId() . ".pdf";

        return new PdfResponse($snappy->getOutputFromHtml($html, $options), $filename);
    }


}

