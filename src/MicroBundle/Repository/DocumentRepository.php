<?php

namespace MicroBundle\Repository;
use PDO;

/**
 * DocumentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DocumentRepository extends \Doctrine\ORM\EntityRepository

{
    /**
     * @param $id
     * @return mixed
     */
    public function findAllExceptId($id) {
        $qb = $this->createQueryBuilder('Document');
        $qb->add('select', 'f')
            ->add('from', 'MicroBundle:Document f')
            ->where('f != :id')
            ->andWhere('f.deleted = false')
            ->setParameter('id', $id);
        return $qb->getQuery()->getResult();
    }


    public function findDocumentDevices($document, $loopNo) {
        $result = $this->createQueryBuilder('Device');

        $dql = $result->select('f','i')
            ->from('MicroBundle:BuildDevice', 'f')
            ->leftJoin('f.docDevices', 'i')
            ->where('i.document =:document')
            ->andWhere('f.loopNo =:loopno')
            ->setParameter('document', $document)
            ->setParameter('loopno', $loopNo)
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);


        return $dql;
    }

    public function findMissingDocumentDevices($document, $building) {
        $em = $this->getEntityManager();
        $RAW_QUERY ="
SELECT build_device.id, build_device.del FROM `build_device`
LEFT JOIN
(
SELECT * FROM `doc_device`
WHERE doc_device.document_id = ?
)
AS doc
ON build_device.id = doc.build_device_id
WHERE doc.build_device_id IS NULL
AND build_device.building_id = ?
        ";


        $statement = $em->getConnection()->prepare($RAW_QUERY);
        // Set parameters
        $statement->bindvalue(1, $document);
        $statement->bindvalue(2, $building);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_OBJ, 'MicroBundle\Entity\BuildDevice');

        return $result;
    }


    public function findMissingDocumentDevicesByLoop($document, $building, $loopNo) {
        $em = $this->getEntityManager();
        $RAW_QUERY ="
SELECT build_device.id, build_device.del FROM `build_device`
LEFT JOIN
(
SELECT * FROM `doc_device`
WHERE doc_device.document_id = ?
AND doc_device.loop_no = ?
)
AS doc
ON build_device.id = doc.build_device_id
WHERE doc.build_device_id IS NULL
AND build_device.building_id = ?
AND build_device.loop_no = ?
        ";


        $statement = $em->getConnection()->prepare($RAW_QUERY);
        // Set parameters
        $statement->bindvalue(1, $document);
        $statement->bindvalue(2, $loopNo);
        $statement->bindvalue(3, $building);
        $statement->bindvalue(4, $loopNo);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_OBJ, 'MicroBundle\Entity\BuildDevice');

        return $result;
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->findBy(['deleted' => false]);
    }

}
