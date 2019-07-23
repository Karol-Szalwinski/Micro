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
        $html = $this->renderView('pdf/show.html.twig', array('fireInspection' => $fireInspection));
        $filename = "Przeglad_PPOZ_nr_" . $fireInspection->getId() . ".pdf";

        return new PdfResponse($this->get('knp_snappy.pdf')->getOutputFromHtml($html), $filename);
    }

    /**
     * Print to pdf FireInspection
     *
     * @Route("/print/{fireInspection}", name="fire_inspection_print")
     * @param FireInspection $fireInspection
     */
    public function printFireInspectionAction(FireInspection $fireInspection)
    {


        return $this->render('pdf/show.html.twig',
        array('fireInspection' => $fireInspection)
    );
    }
}

