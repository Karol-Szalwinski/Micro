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
            new TwigFilter('priceLow', [$this, 'formatPriceLow']),
        ];
    }

    public function formatPrice($number, $decimals = 2, $decPoint = ',', $thousandsSep = ' ')
    {
        $number /= 100;
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = $price. ' ZŁ';

        return $price;
    }

    public function formatPriceLow($number, $decimals = 2, $decPoint = ',', $thousandsSep = ' ')
    {
        $number /= 100;
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = $price. ' zł';

        return $price;
    }
}