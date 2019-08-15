<?php

namespace MicroBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use MicroBundle\Entity\MyCompany;

final class MyCompanyRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        // The second parameter is the Entity this Repository uses
        parent::__construct($registry, MyCompany::class);
    }

    public function findOneById($id)
    {

        return $this->findOneBy(['id' => $id]);

    }

    public function createDefault(): MyCompany
    {
        $myCompany = new Mycompany(
            '1',
            'Przykładowa nazwa',
            'Przykładowa ulica',
            '00-000',
            'Przykładowe miasto',
            'Numer NIP',
            '000-000-000'
        );
        $this->save($myCompany);

        return $myCompany;
    }

    public function save(MyCompany $myCompany): void
    {
        $this->_em->persist($myCompany);
        $this->_em->flush();
    }
}
