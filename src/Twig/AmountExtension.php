<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AmountExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // JAI UTILISER symfony console make:twig-extension
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('amount', [$this, 'amount']),
        ];
    }

    public function amount($value)
    {
        // ...
        $finalValue = $value/100;

        $finalValue = number_format($finalValue, 2, ',', ' ');

        return $finalValue . ' €';
    }
}
