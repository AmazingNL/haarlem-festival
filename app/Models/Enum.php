<?php

declare(strict_types=1);
namespace App\Models\Enum;

enum UserRole: string{
    case admin = 'admin';
    case customer = 'customer';
    case employee = 'employee';
    }

enum OrderStatus: string
{
    case pending = 'pending';
    case paid = 'paid';
    case cancelled = 'cancelled';
    case expired = 'expired';
}

enum PaymentStatus: string
{
    case pending = 'pending';
    case paid = 'paid';
    case failed = 'failed';
    case refunded = 'refunded';
}

enum TicketStatus: string
{
    case valid = 'valid';
    case scanned = 'scanned';
    case cancelled = 'cancelled';
}

enum ProgramItemSource: string
{
    case ticket = 'ticket';
    case saved = 'saved';
}

enum PageStatus: string
{
    case draft = 'draft';
    case published = 'published';
    case archived = 'archived';
}

enum SectionType: string
{
    case TEXT_BLOCK = 'text_block';
    case RESTAURANT_CARD = 'restaurant_card';
    case IMAGE_TEXT = 'image_text';
    case HERO = 'hero';
    case FEATURE = 'feature';
    case IMAGE_LEFT = 'image_left';
    case IMAGE_RIGHT = 'image_right';
    case JOURNEY = 'journey';
    case STAT = 'stat';
    case PAGE_TIMELINE = 'timeline';
    case TRANSPORT = 'transport';
    case TWO_IMAGE_ROW = 'two_image_row';
    case VENUE = 'venue';
    case CARDS_GRID = 'cards_grid';
    case RESTAURANTS_CARD = 'restaurants_card';
    case WELCOME_BANNER = "welcome_banner";
    case WELCOME_BANNER_CARD = 'welcome_banner_card';
    case GALLERY = "gallery";
    case HOME_GALLERY = "home_gallery";
    case STORIES_HERO = 'stories_hero';
    case WHAT_IS_STORIES = 'what_is_stories';
    case STORIES_PREVIEW = 'stories_preview';
    case STORYTELLING_SCHEDULE = 'storytelling_schedule';
    case HAARLEM_UNIQUE = 'haarlem_unique';
    case HAARLEM_TASTE = 'haarlem_taste';
    case HISTORY_HERO = 'history_hero';
    case HISTORY_TIMELINE = 'history_timeline';
    case HISTORY_GALLERY = 'history_gallery';
    case HISTORY_FEATURED_LOCATIONS = 'history_featured_locations';
    case HISTORY_ROUTE = 'history_route';
    case HISTORY_INFO = 'history_info';
    case HISTORY_CTA = 'history_cta';
    case HISTORY_PAGE_NAV = 'history_page_nav';
    case HISTORY_BOOK_TOUR_HERO = 'history_book_tour_hero';
    case HISTORY_BOOK_TOUR_BOOKING = 'history_book_tour_booking';
    case HISTORY_BOOK_TOUR_ROUTE = 'history_book_tour_route';
    case HISTORY_BOOK_TOUR_SCHEDULE = 'history_book_tour_schedule';
    case HISTORY_BOOK_TOUR_PRICING = 'history_book_tour_pricing';
    case HISTORY_BOOK_TOUR_NOTICE = 'history_book_tour_notice';
    case HISTORY_BOOK_TOUR_ALERT = 'history_book_tour_alert';
    case HISTORY_ROUTE_MAP_HERO = 'history_route_map_hero';
    case HISTORY_ROUTE_MAP_STOPS = 'history_route_map_stops';
    case HISTORY_ROUTE_MAP_DIRECTIONS = 'history_route_map_directions';
    case HISTORY_ROUTE_MAP_CTA = 'history_route_map_cta';
    case HISTORY_ST_BAVO_HERO = 'history_st_bavo_hero';
    case HISTORY_ST_BAVO_FACTS = 'history_st_bavo_facts';
    case HISTORY_ST_BAVO_ARTICLE = 'history_st_bavo_article';
    case HISTORY_ST_BAVO_SIDEBAR = 'history_st_bavo_sidebar';
    case HISTORY_ST_BAVO_ROUTE_CTA = 'history_st_bavo_route_cta';
    case HISTORY_MOLEN_HERO = 'history_molen_hero';
    case HISTORY_MOLEN_FACTS = 'history_molen_facts';
    case HISTORY_MOLEN_ARTICLE = 'history_molen_article';
    case HISTORY_MOLEN_SIDEBAR = 'history_molen_sidebar';
    case HISTORY_MOLEN_ROUTE_CTA = 'history_molen_route_cta';
    case RESERVATION = 'reservation';
}

