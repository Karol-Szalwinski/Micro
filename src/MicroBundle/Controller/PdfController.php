<?php
/**
 * Created by PhpStorm.
 * User: karol
 * Date: 12.07.19
 * Time: 17:15
 */

namespace MicroBundle\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MicroBundle\Entity\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("fireinspection")
 */
class PdfController extends Controller
{


    /**
     * Print to pdf Document
     *
     * @Route("/pdf/{fireInspection}", name="fire_inspection_pdf")
     * @param Document $fireInspection
     * @return PdfResponse
     */
    public function pdfFireInspectionAction(Document $fireInspection)
    {
        $prepareService = $this->get('prepareHtmlToPdf');
        $html = $prepareService->getContent($fireInspection);
        $header = $prepareService->getHeader($fireInspection);
        $footer = $prepareService->getFooter($fireInspection);

        $marginBottom = ($fireInspection->getPdfSettings()->getShowStamp() || !empty($fireInspection->getPdfSettings()->getInspectors()))
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
        $filename = "Przeglad_PPOZ_nr_" . $fireInspection->getId() . ".pdf";

        return new PdfResponse($snappy->getOutputFromHtml($html, $options), $filename);
    }


}

