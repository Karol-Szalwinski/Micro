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
use MicroBundle\Entity\FireInspection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("fireinspection")
 */
class PdfController extends Controller
{


    /**
     * Print to pdf FireInspection
     *
     * @Route("/pdf/{fireInspection}", name="fire_inspection_pdf")
     * @param FireInspection $fireInspection
     * @return PdfResponse
     */
    public function pdfFireInspectionAction(FireInspection $fireInspection)
    {
        $myCompany = $this->get('mycompany')->getOrCreateDefaultMyCompany();
        $html = $this->renderView('pdf/fire-inspection-content.html.twig',
            array('fireInspection' => $fireInspection));

        $header = $this->renderView( 'pdf/fire-inspection-header.html.twig', array( 'mycompany' => $myCompany) );
        $footer = $this->renderView( 'pdf/fire-inspection-footer.html.twig' );



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
            'margin-bottom' => '25mm',
            'header-spacing'=> '3',
            'footer-right'     => "[page]"

        ];
        $snappy =  $this->get('knp_snappy.pdf');


        $snappy->setOption('header-html', $header);
        $snappy->setOption('footer-html', $footer);
        $snappy->setTimeout(40);
        $filename = "Przeglad_PPOZ_nr_" . $fireInspection->getId() . ".pdf";

        return new PdfResponse($snappy->getOutputFromHtml($html, $options), $filename);
    }


}

