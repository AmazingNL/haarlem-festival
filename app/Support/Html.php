<?php

declare(strict_types=1);

namespace App\Support;

use HTMLPurifier;
use HTMLPurifier_Config;

/**
 * Sanitizes admin-authored WYSIWYG HTML before it is rendered, removing scripts,
 * inline event handlers and javascript: URIs while keeping safe formatting markup.
 *
 * Used at output time on rich-text fields that must render as HTML (and therefore
 * cannot be escaped with htmlspecialchars()).
 */
final class Html
{
    private static ?HTMLPurifier $purifier = null;

    public static function clean(?string $html): string
    {
        $html = (string) $html;
        if (trim($html) === '') {
            return '';
        }

        return self::purifier()->purify($html);
    }

    private static function purifier(): HTMLPurifier
    {
        if (self::$purifier === null) {
            $config = HTMLPurifier_Config::createDefault();
            // No writable cache directory required in this environment.
            $config->set('Cache.DefinitionImpl', null);
            // Allow links/images to keep working; HTMLPurifier still strips
            // unsafe schemes and attributes from them.
            $config->set('HTML.TargetBlank', true);
            $config->set('Attr.AllowedFrameTargets', ['_blank']);

            self::$purifier = new HTMLPurifier($config);
        }

        return self::$purifier;
    }
}
