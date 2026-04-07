<?php

namespace App\ViewModels;
use App\ViewModels\yummy\Gallery;
use App\ViewModels\yummy\TextBlock;
use App\ViewModels\yummy\WelcomeBanner;
use App\ViewModels\yummy\HaarlemUnique;


class SectionFactory 
{
    public static function returnSectionClass(string $type, ): ?string
    {
        $map = [
            'text_block' => TextBlock::class,
            'welcome_banner' => WelcomeBanner::class,
            'gallery' => Gallery::class,
            'haarlem_unique' => HaarlemUnique::class 

            
        ];
        return $map[$type];
    }


}