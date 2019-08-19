<?php

namespace MicroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Pdf
 *
 * @ORM\Table(name="pdf_document")
 * @ORM\Entity(repositoryClass="MicroBundle\Repository\PdfDocumentRepository")
 */
class PdfDocument
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="pdf_file_name", type="string", nullable=true)
     */
    private $pdfFileName;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


   

    /**
     * Set pdfFileName.
     *
     * @param string|null $pdfFileName
     *
     * @return PdfDocument
     */
    public function setPdfFileName($pdfFileName = null)
    {
        $this->pdfFileName = $pdfFileName;

        return $this;
    }

    /**
     * Get pdfFileName.
     *
     * @return string|null
     */
    public function getPdfFileName()
    {
        return $this->pdfFileName;
    }
}
