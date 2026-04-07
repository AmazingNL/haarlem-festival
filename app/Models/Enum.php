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
    case CTA = 'cta';
    case TEXT_BLOCK = 'text_block';
    case IMAGE_TEXT = 'image_text';
    case RESTAURANTS_CARD = 'restaurants_card';
    case WELCOME_BANNER = "welcome_banner";
    case GALLERY = "gallery";
    case HAARLEM_UNIQUE = "haarlem_unique";
}

