<?php

namespace MicroBundle\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use MicroBundle\Entity\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("document")
 */
class PdfDocumentController extends PdfController
{


    /**
     * Print to pdf Document
     *
     * @Route("/pdf/document/{documentId}", name="document_pdf")
     * @param $documentId
     * @return PdfResponse
     * @internal param Document $document
     */
    public function pdfCreateAction( $documentId) : PdfResponse
    {
        $em = $this->getDoctrine()->getManager();
        $document = $em->getRepository('MicroBundle:Document')->findOneById($documentId);
        $prepareService = $this->get('generateHtmlFromDocument');
        $html = $prepareService->getContent($document);
        $header = $prepareService->getHeader($document);
        $footer = $prepareService->getFooter($document);

        $marginBottom = ($document->getPdfSettings()->getShowStamp() || !empty($document->getPdfSettings()->getInspectors()))
            ? '45mm' : '25mm';
        $filename = "Document_nr_" . $document->getId() . ".pdf";
        return $this->generatePdf($filename, $header, $footer, $marginBottom, $html);
    }




}

