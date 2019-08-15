<?php

namespace MicroBundle\Services;



use MicroBundle\Entity\MyCompany;
use MicroBundle\Repository\MyCompanyRepository;


final class MyCompanyService
{
    private $myCompanyRepository;

    /**
     * MyCompanyService constructor.
     * @param MyCompanyRepository $myCompanyRepository
     */
    public function __construct(MyCompanyRepository $myCompanyRepository){


        $this->myCompanyRepository = $myCompanyRepository;
    }
    public function getOrCreateDefaultMyCompany(): MyCompany
    {

        $myCompany = $this->myCompanyRepository->findOneById(1);

        $myCompany = ($myCompany) ? $myCompany : $this->myCompanyRepository->createDefault();

        return $myCompany;
    }
    public function updateMyCompany(MyCompany $myCompany): void
    {
        $this->myCompanyRepository->save($myCompany);

    }
}

