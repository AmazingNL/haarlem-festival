<?php

namespace App\ViewModels;
use App\ViewModels\yummy\TextBlock;
use App\ViewModels\yummy\WelcomeBanner;

class SectionFactory 
{
    public static function returnSectionClass(string $type, ): ?string
    {
        $map = [
            'text_block' => TextBlock::class,
            'welcome_banner' => WelcomeBanner::class,

        ];
        return $map[$type];
    }


}