<?php

namespace MicroBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
        ];
    }

    public function formatPrice($number, $decimals = 2, $decPoint = ',', $thousandsSep = ' ')
    {
        $number /= 100;
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = $price. ' ZŁ';

        return $price;
    }
}