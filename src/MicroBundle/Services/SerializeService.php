<?php

namespace MicroBundle\Services;


use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializeService
{

    public function serlializeJson($object, $circularReferenceLimit, $ignoredAttributes)
    {
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes($ignoredAttributes);
        $normalizer->setCircularReferenceLimit($circularReferenceLimit);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $encoder = new JsonEncoder();
        $serializer = new Serializer([$normalizer], [$encoder]);

        return $serializer->serialize($object, 'json');

    }
}