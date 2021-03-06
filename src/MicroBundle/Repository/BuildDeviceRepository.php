<?php

namespace MicroBundle\Repository;

/**
 * BuildDeviceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BuildDeviceRepository extends \Doctrine\ORM\EntityRepository
{
    public function getHighestNumber($building, $loopNo)
    {
        $result = $this->createQueryBuilder('BuildDevice');

        $dql = $result->select(' MAX(b.number) as maxNumber')
            ->from('MicroBundle:BuildDevice', 'b')
            ->where('b.loopNo =:loopno')
            ->andWhere('b.building =:building')
            ->setParameter('loopno', $loopNo)
            ->setParameter('building', $building)
            ->getQuery()
            ->getSingleResult();


        return $dql;
    }

    public function countDevicesByLoop( $building)
    {
        $em = $this->getEntityManager();
        $RAW_QUERY ="
SELECT build_device.loop_no, COUNT(build_device.loop_no) AS devicesCount
FROM `build_device`
WHERE build_device.building_id = ?
AND build_device.del = false
GROUP BY build_device.loop_no
        ";


        $statement = $em->getConnection()->prepare($RAW_QUERY);
        // Set parameters
        $statement->bindvalue(1, $building);
        $statement->execute();

        $result = $statement->fetchAll();

        return $result;
    }

}
