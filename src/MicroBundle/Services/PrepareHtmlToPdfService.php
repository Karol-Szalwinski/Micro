<?php

namespace MicroBundle\Services;


use MicroBundle\Entity\Document;

/**
 * Class PrepareFireInspectionToPdfService
 * @package MicroBundle\Services
 */
final class PrepareHtmlToPdfService
{
    private $myCompany;
    private $templating;

  
    public function __construct(MyCompanyService $myCompany, \Twig_Environment $templating){

        $this->myCompany = $myCompany;
        $this->templating = $templating;
    }


    /**
     * @param Document $fireInspection
     * @return string
     */
    public function getContent(Document $fireInspection): string
    {
        $content = $this->templating->render('pdf/fire-inspection-content.html.twig',
            array('fireInspection' => $fireInspection));

        return $content;
    }

    /**
     * @param Document $fireInspection
     * @return string
     */
    public function getHeader(Document $fireInspection): string
    {
        $header = $this->templating->render( 'pdf/fire-inspection-header.html.twig',
            array( 'mycompany' => $this->myCompany->getOrCreateDefaultMyCompany()) );

        return $header;
    }

    /**
     * @param Document $fireInspection
     * @return string
     */
    public function getFooter(Document $fireInspection): string
    {
        $footer = $this->templating->render( 'pdf/fire-inspection-footer.html.twig',
            array('fireInspection' => $fireInspection,
                    'mycompany' => $this->myCompany->getOrCreateDefaultMyCompany()
            ));

        return $footer;
    }



}