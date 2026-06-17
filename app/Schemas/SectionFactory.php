<?php

namespace App\Schemas;

use App\Schemas\home\HomeCardsGrid;
use App\Schemas\home\HomeFeature;
use App\Schemas\home\HomeGallery;
use App\Schemas\home\HomeHero;
use App\Schemas\home\HomeImageLeft;
use App\Schemas\home\HomeImageRight;
use App\Schemas\home\HomeTransport;
use App\Schemas\history\HistoryBookTourAlert;
use App\Schemas\history\HistoryBookTourBooking;
use App\Schemas\history\HistoryBookTourHero;
use App\Schemas\history\HistoryBookTourNotice;
use App\Schemas\history\HistoryBookTourPricing;
use App\Schemas\history\HistoryBookTourRoute;
use App\Schemas\history\HistoryBookTourSchedule;
use App\Schemas\history\HistoryCta;
use App\Schemas\history\HistoryFeaturedLocations;
use App\Schemas\history\HistoryGallery;
use App\Schemas\history\HistoryHero;
use App\Schemas\history\HistoryInfo;
use App\Schemas\history\HistoryMolenArticle;
use App\Schemas\history\HistoryMolenFacts;
use App\Schemas\history\HistoryMolenHero;
use App\Schemas\history\HistoryMolenRouteCta;
use App\Schemas\history\HistoryMolenSidebar;
use App\Schemas\history\HistoryPageNav;
use App\Schemas\history\HistoryRoute;
use App\Schemas\history\HistoryRouteMapCta;
use App\Schemas\history\HistoryRouteMapDirections;
use App\Schemas\history\HistoryRouteMapHero;
use App\Schemas\history\HistoryRouteMapStops;
use App\Schemas\history\HistoryStBavoArticle;
use App\Schemas\history\HistoryStBavoFacts;
use App\Schemas\history\HistoryStBavoHero;
use App\Schemas\history\HistoryStBavoRouteCta;
use App\Schemas\history\HistoryStBavoSidebar;
use App\Schemas\history\HistoryTimeline;
use App\Schemas\yummy\Gallery;
use App\Schemas\yummy\HaarlemUnique;
use App\Schemas\yummy\Reservation;
use App\Schemas\yummy\RestaurantCard;
use App\Schemas\yummy\TextBlock;
use App\Schemas\yummy\WelcomeBanner;
use App\Schemas\yummy\WelcomeBannerCard;
use App\Schemas\stories\StoriesHero;
use App\Schemas\stories\WhatIsStories;
use App\Schemas\stories\StoriesPreview;
use App\Schemas\stories\StorytellingSchedule;
use App\Schemas\stories\StoriesBooking;

// Map a section type string to the ViewModel class that defines its CMS fields.
class SectionFactory
{
    // Return the correct Schema class for the requested section type.
    public static function returnSectionClass(string $type): ?string
    {
        $map = [
            'hero' => HomeHero::class,
            'feature' => HomeFeature::class,
            'home_gallery' => HomeGallery::class,
            'gallery' => Gallery::class,
            'image_left' => HomeImageLeft::class,
            'image_right' => HomeImageRight::class,
            'cards_grid' => HomeCardsGrid::class,
            'transport' => HomeTransport::class,
            'text_block' => TextBlock::class,
            'restaurant_card' => RestaurantCard::class,
            'welcome_banner' => WelcomeBanner::class,
            'welcome_banner_card' => WelcomeBannerCard::class,
            'haarlem_unique' => HaarlemUnique::class,
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
            'reservation'           => Reservation::class,
            'stories_hero'          => StoriesHero::class,
            'what_is_stories'       => WhatIsStories::class,
            'stories_preview'       => StoriesPreview::class,
            'storytelling_schedule' => StorytellingSchedule::class,
            'stories_booking'       => StoriesBooking::class,
        ];

        return $map[$type] ?? null;
    }
}
