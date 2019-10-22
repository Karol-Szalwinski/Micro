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
class PdfOffertController extends PdfController
{


    /**
     * Print to pdf Document
     *
     * @Route("/pdf/offert/{offertId}", name="offert_pdf")
     * @param $offertId
     * @return PdfResponse
     */
    public function pdfCreateAction($offertId) : PdfResponse
    {
        $em = $this->getDoctrine()->getManager();
        $offert = $em->getRepository('MicroBundle:Offert')->findOneById($offertId);
        $prepareService = $this->get('generateHtmlFromOffert');
        $html = $prepareService->getContent($offert);
        $header = $prepareService->getHeader($offert);
        $footer = $prepareService->getFooter($offert);

        $marginBottom =  '25mm';
        $filename =  $offert->getName() . ".pdf";
        return $this->generatePdf($filename, $header, $footer, $marginBottom, $html);
    }




}

