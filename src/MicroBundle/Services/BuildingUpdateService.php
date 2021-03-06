<?php
/**
 * Update last service date in FireInspectedDevices in Building
 */

namespace MicroBundle\Services;


use MicroBundle\Entity\Building;
use MicroBundle\Entity\Document;

class BuildingUpdateService
{
    private $em;

    /**
     * BuildingUpdateService constructor.
     * @param $em
     */
    public function __construct($em)
    {
        $this->em = $em;
    }


    public function updateLastServiceDate(Building $building)
    {

        return $this->em->getRepository('MicroBundle:Building')->updateLastServiceDates($building->getId());
    }

    public function updateLastServiceDateDocument(Document $document)
    {

        return $this->em->getRepository('MicroBundle:Building')
            ->updateLastServiceDateExceptOneDocument($document->getBuilding()->getId(), $document->getId());
    }

}