<?php

namespace MicroBundle\Repository;
use MicroBundle\Entity\Building;

/**
 * BuildingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BuildingRepository extends \Doctrine\ORM\EntityRepository
{
    public function updateLastServiceDates($building)
    {
        $em = $this->getEntityManager();
        $RAW_QUERY ="UPDATE build_device AS Tabela1
  INNER JOIN (

               SELECT
                 BuildDeviceAll.id,
                 BuildDeviceAll.last_service_date,
                 DocDevices.new_date
               FROM
                 (SELECT
                    build_device.id,
                    build_device.last_service_date
                  FROM build_device

                  WHERE build_device.building_id = ?
                        AND build_device.del = FALSE)
                   AS BuildDeviceAll

                 LEFT JOIN (
                             SELECT
                               build_device.id               AS idd,
                               build_device.name,
                               build_device.last_service_date,
                               MAX(document.inspection_date) AS new_date

                             FROM build_device
                               INNER JOIN doc_device
                                 ON build_device.id = doc_device.build_device_id
                               INNER JOIN document
                                 ON document.id = doc_device.document_id

                             WHERE doc_device.visible = TRUE
                             GROUP BY build_device.id
                           )
                 AS DocDevices
                   ON BuildDeviceAll.id = DocDevices.idd
             ) AS Tabela2
    ON Tabela1.id = Tabela2.id
SET Tabela1.last_service_date = Tabela2.new_date; ";


        $statement = $em->getConnection()->prepare($RAW_QUERY);
        // Set parameters
        $statement->bindvalue(1, $building);
        $statement->execute();

    }

    public function updateLastServiceDateExceptOneDocument($building, $fireInspection)
    {
        $em = $this->getEntityManager();
        $RAW_QUERY ="UPDATE build_device AS Tabela1
  INNER JOIN (

               SELECT
                 BuildDeviceAll.id,
                 BuildDeviceAll.last_service_date,
                 DocDevices.new_date
               FROM
                 (SELECT
                    build_device.id,
                    build_device.last_service_date
                  FROM build_device

                  WHERE build_device.building_id = ?
                        AND build_device.del = FALSE)
                   AS BuildDeviceAll

                 LEFT JOIN (
                             SELECT
                               build_device.id               AS idd,
                               build_device.name,
                               build_device.last_service_date,
                               MAX(document.inspection_date) AS new_date

                             FROM build_device
                               INNER JOIN doc_device
                                 ON build_device.id = doc_device.build_device_id
                               INNER JOIN document
                                 ON document.id = doc_device.document_id

                             WHERE doc_device.visible = TRUE
                             AND document.id <> ?
                             GROUP BY build_device.id
                           )
                 AS DocDevices
                   ON BuildDeviceAll.id = DocDevices.idd
             ) AS Tabela2
    ON Tabela1.id = Tabela2.id
SET Tabela1.temp_service_date = Tabela2.new_date
        ";


        $statement = $em->getConnection()->prepare($RAW_QUERY);
        // Set parameters
        $statement->bindvalue(1, $building);
        $statement->bindvalue(2, $fireInspection);
        $statement->execute();

    }
}
