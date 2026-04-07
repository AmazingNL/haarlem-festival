<?php

namespace App\ViewModels;

use App\ViewModels\history\HistoryCta;
use App\ViewModels\history\HistoryBookTourAlert;
use App\ViewModels\history\HistoryBookTourBooking;
use App\ViewModels\history\HistoryBookTourHero;
use App\ViewModels\history\HistoryBookTourNotice;
use App\ViewModels\history\HistoryBookTourPricing;
use App\ViewModels\history\HistoryBookTourRoute;
use App\ViewModels\history\HistoryBookTourSchedule;
use App\ViewModels\history\HistoryFeaturedLocations;
use App\ViewModels\history\HistoryGallery;
use App\ViewModels\history\HistoryHero;
use App\ViewModels\history\HistoryInfo;
use App\ViewModels\history\HistoryPageNav;
use App\ViewModels\history\HistoryRouteMapCta;
use App\ViewModels\history\HistoryRouteMapDirections;
use App\ViewModels\history\HistoryRouteMapHero;
use App\ViewModels\history\HistoryRouteMapStops;
use App\ViewModels\history\HistoryRoute;
use App\ViewModels\history\HistoryStBavoArticle;
use App\ViewModels\history\HistoryStBavoFacts;
use App\ViewModels\history\HistoryStBavoHero;
use App\ViewModels\history\HistoryStBavoRouteCta;
use App\ViewModels\history\HistoryStBavoSidebar;
use App\ViewModels\history\HistoryMolenArticle;
use App\ViewModels\history\HistoryMolenFacts;
use App\ViewModels\history\HistoryMolenHero;
use App\ViewModels\history\HistoryMolenRouteCta;
use App\ViewModels\history\HistoryMolenSidebar;
use App\ViewModels\history\HistoryTimeline;
use App\ViewModels\yummy\TextBlock;
use App\ViewModels\yummy\WelcomeBanner;

class SectionFactory 
{
    public static function returnSectionClass(string $type, ): ?string
    {
        $map = [
            'text_block' => TextBlock::class,
            'welcome_banner' => WelcomeBanner::class,
            'history_hero' => HistoryHero::class,
            'history_timeline' => HistoryTimeline::class,
            'history_gallery' => HistoryGallery::class,
            'history_featured_locations' => HistoryFeaturedLocations::class,
            'history_route' => HistoryRoute::class,
            'history_info' => HistoryInfo::class,
            'history_cta' => HistoryCta::class,
            'history_page_nav' => HistoryPageNav::class,
            'history_book_tour_hero' => HistoryBookTourHero::class,
            'history_book_tour_booking' => HistoryBookTourBooking::class,
            'history_book_tour_route' => HistoryBookTourRoute::class,
            'history_book_tour_schedule' => HistoryBookTourSchedule::class,
            'history_book_tour_pricing' => HistoryBookTourPricing::class,
            'history_book_tour_notice' => HistoryBookTourNotice::class,
            'history_book_tour_alert' => HistoryBookTourAlert::class,
            'history_route_map_hero' => HistoryRouteMapHero::class,
            'history_route_map_stops' => HistoryRouteMapStops::class,
            'history_route_map_directions' => HistoryRouteMapDirections::class,
            'history_route_map_cta' => HistoryRouteMapCta::class,
            'history_st_bavo_hero' => HistoryStBavoHero::class,
            'history_st_bavo_facts' => HistoryStBavoFacts::class,
            'history_st_bavo_article' => HistoryStBavoArticle::class,
            'history_st_bavo_sidebar' => HistoryStBavoSidebar::class,
            'history_st_bavo_route_cta' => HistoryStBavoRouteCta::class,
            'history_molen_hero' => HistoryMolenHero::class,
            'history_molen_facts' => HistoryMolenFacts::class,
            'history_molen_article' => HistoryMolenArticle::class,
            'history_molen_sidebar' => HistoryMolenSidebar::class,
            'history_molen_route_cta' => HistoryMolenRouteCta::class,
        ];

        return $map[$type] ?? null;
    }


}
