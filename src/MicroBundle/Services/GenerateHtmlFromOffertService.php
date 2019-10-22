<?php

namespace MicroBundle\Services;


use MicroBundle\Entity\Offert;

/**
 * Class GenerateHtmlFromOffertService
 * @package MicroBundle\Services
 */
final class GenerateHtmlFromOffertService
{
    private $myCompany;
    private $templating;

  
    public function __construct(MyCompanyService $myCompany, \Twig_Environment $templating){

        $this->myCompany = $myCompany;
        $this->templating = $templating;
    }


    /**
     * @param Offert $offert
     * @return string
     */
    public function getContent(Offert $offert): string
    {
        $content = $this->templating->render('pdf/offert-content.html.twig',
            array('offert' => $offert));

        return $content;
    }

    /**
     * @param Offert $offert
     * @return string
     */
    public function getHeader(Offert $offert): string
    {
        $header = $this->templating->render( 'pdf/offert-header.html.twig',
            array( 'mycompany' => $this->myCompany->getOrCreateDefaultMyCompany()) );

        return $header;
    }

    /**
     * @param Offert $offert
     * @return string
     */
    public function getFooter(Offert $offert): string
    {
        $footer = $this->templating->render( 'pdf/offert-footer.html.twig',
            array('offert' => $offert,
                    'mycompany' => $this->myCompany->getOrCreateDefaultMyCompany()
            ));

        return $footer;
    }



}