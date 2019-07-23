<?php
/**
 * Update last service date in FireInspectedDevices in Building
 */

namespace MicroBundle\Services;


use MicroBundle\Entity\Building;

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
        $FireProtectionDevices = $building->getFireProtectionDevices();
        foreach ($FireProtectionDevices as $FireProtectionDevice) {
            $date = null;
            foreach ($FireProtectionDevice->getInspectedDevices() as $inspectedDevice) {
                if ($inspectedDevice->getVisible()) {
                    $tempDate = $inspectedDevice->getFireInspection()->getInspectionDate();
                    $date = ($date > $tempDate) ? $date : $tempDate;
                }
            }

            $FireProtectionDevice->setLastServiceDate($date);
        }

        $this->em->flush();
        return true;
    }

}