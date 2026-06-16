<?php

declare(strict_types=1);

namespace App\Support;

final class PaymentProvider
{
  private const LABELS = [
    'ideal' => 'iDEAL',
    'card' => 'Credit Card',
    'card-ideal' => 'Credit Card / iDEAL',
    'stripe-ideal' => 'iDEAL',
    'stripe-card' => 'Credit Card',
    'stripe-ideal-card' => 'Credit Card / iDEAL',
  ];

  private const STRIPE_METHODS = [
    'ideal' => ['ideal'],
    'card' => ['card'],
    'card-ideal' => ['ideal', 'card'],
    'stripe-card' => ['card'],
    'stripe-ideal-card' => ['ideal', 'card'],
  ];

  public static function normalize(string $provider): string
  {
    $provider = trim($provider);
    $allowed = ['ideal', 'card', 'card-ideal', 'stripe-ideal', 'stripe-card', 'stripe-ideal-card'];

    if (!in_array($provider, $allowed, true)) {
      return 'ideal';
    }

    return match ($provider) {
      'stripe-ideal' => 'ideal',
      'stripe-card' => 'card',
      'stripe-ideal-card' => 'card-ideal',
      default => $provider,
    };
  }

  public static function label(?string $provider): string
  {
    $normalized = self::normalize((string) $provider);

    return self::LABELS[$normalized] ?? self::LABELS[(string) $provider] ?? 'Secure Payment';
  }

  /** @return list<string> */
  public static function stripeMethodTypes(string $provider): array
  {
    $normalized = self::normalize($provider);

    return self::STRIPE_METHODS[$normalized] ?? ['ideal'];
  }
}
