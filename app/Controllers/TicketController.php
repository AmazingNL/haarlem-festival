<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\ITicketService;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

/**
 * Serves QR code images for festival tickets.
 *
 * Each ticket row in the database has a unique qr_token (64-char hex).
 * GET /qr/{token} validates the token exists, then streams an SVG QR image.
 */
final class TicketController extends BaseController
{
    private ITicketService $ticketService;

    /** @param ITicketService $ticketService */
    public function __construct(ITicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    /**
     * Stream a QR code SVG for the given ticket token.
     *
     * @param  string $token 64-char hex qr_token from the URL segment.
     * @return void
     * @throws void — aborts with 400/404 on invalid input.
     */
    public function qrImage(string $token): void
    {
        if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
            $this->abort(400, 'Invalid ticket token.');
        }

        $ticket = $this->ticketService->findByToken($token);
        if ($ticket === null) {
            $this->abort(404, 'Ticket not found.');
        }

        $options = new QROptions;
        $options->outputBase64 = false;

        header('Content-Type: image/svg+xml');
        header('Cache-Control: private, max-age=3600');
        echo (new QRCode($options))->render($token);
        exit;
    }
}
