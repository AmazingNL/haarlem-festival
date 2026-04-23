<?php

namespace App\ViewModels;
use App\ViewModels\yummy\Gallery;
use App\ViewModels\yummy\RestaurantCard;
use App\ViewModels\yummy\TextBlock;
use App\ViewModels\yummy\WelcomeBanner;
use App\ViewModels\yummy\WelcomeBannerCard;
use App\ViewModels\yummy\HaarlemUnique;


class SectionFactory 
{
    public static function returnSectionClass(string $type, ): ?string
    {
        $map = [
            'text_block' => TextBlock::class,
            'welcome_banner' => WelcomeBanner::class,
            'welcome_banner_card' => WelcomeBannerCard::class,
            'gallery' => Gallery::class,
            'haarlem_unique' => HaarlemUnique::class,
            'restaurant_card' => RestaurantCard::class,

            
        ];
        return $map[$type] ?? null;
    }


}