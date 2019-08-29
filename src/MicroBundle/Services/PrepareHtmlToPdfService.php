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
     * @param Document $document
     * @return string
     */
    public function getContent(Document $document): string
    {
        $content = $this->templating->render('pdf/fire-inspection-content.html.twig',
            array('document' => $document));

        return $content;
    }

    /**
     * @param Document $document
     * @return string
     */
    public function getHeader(Document $document): string
    {
        $header = $this->templating->render( 'pdf/fire-inspection-header.html.twig',
            array( 'mycompany' => $this->myCompany->getOrCreateDefaultMyCompany()) );

        return $header;
    }

    /**
     * @param Document $document
     * @return string
     */
    public function getFooter(Document $document): string
    {
        $footer = $this->templating->render( 'pdf/fire-inspection-footer.html.twig',
            array('document' => $document,
                    'mycompany' => $this->myCompany->getOrCreateDefaultMyCompany()
            ));

        return $footer;
    }



}