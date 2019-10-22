<?php

namespace MicroBundle\Controller;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



abstract class PdfController extends Controller
{
    /**
     * @param $filename
     * @param $header
     * @param $footer
     * @param $marginBottom
     * @param $html
     * @return PdfResponse
     */
    protected function generatePdf($filename, $header, $footer, $marginBottom, $html): PdfResponse
    {
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
            'header-spacing' => '3',

        ];
        $snappy = $this->get('knp_snappy.pdf');


        $snappy->setOption('header-html', $header);
        $snappy->setOption('footer-html', $footer);
        $snappy->setTimeout(40);


        return new PdfResponse($snappy->getOutputFromHtml($html, $options), $filename);
    }
    abstract function pdfCreateAction($object) : PdfResponse;
}