<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

/**
 * A booking strategy knows how to re-validate one kind of "My Program" item
 * (event ticket, history tour, restaurant reservation) at checkout.
 *
 * CheckoutValidationService dispatches each cart item to the strategy whose
 * handledType() matches the item's "type", instead of a hard-coded match.
 */
interface IBookingStrategy
{
    /** The My-Program item "type" this strategy handles, e.g. "event-ticket". */
    public function handledType(): string;

    /**
     * Re-validate and normalize a single My-Program item of the handled type,
     * re-deriving price/quantity from source data (never trusting the cart).
     *
     * @param array<string, mixed> $item
     * @return array<string, mixed>
     */
    public function validateProgramItem(array $item): array;
}
