<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\Interfaces\ITicketService;
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

    /**
     * Render the employee ticket-scanning page.
     *
     * @return void
     */
    public function scanPage(): void
    {
        $this->view('admin_dashboard/scan_ticket', ['title' => 'Scan Ticket'], layout: 'admin_dashboard');
    }

    /**
     * Accept a POST scan request and return JSON with the scan result.
     *
     * @param  string $token 64-char hex qr_token from the URL segment.
     * @return void
     */
    public function scan(string $token): void
    {
        if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
            $this->json(['result' => 'not_found'], 400);
        }

        $result = $this->ticketService->markScanned($token);
        $statusCode = $result['result'] === 'ok' ? 200 : 422;
        $this->json($result, $statusCode);
    }
}
