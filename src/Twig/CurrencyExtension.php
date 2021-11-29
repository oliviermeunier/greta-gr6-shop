<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CurrencyExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('format_price', [$this, 'formatPrice'], [
                'is_safe' => ['html']
            ]),
        ];
    }

    public function formatPrice($priceInCents)
    {
        $cents = $priceInCents % 100;
        $euros = ($priceInCents - $cents) / 100;

        $formattedPrice = "$euros, <span class=\"cents\">$cents</span> €";
        
        return $formattedPrice;
    }
}
