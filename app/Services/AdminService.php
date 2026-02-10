<?php

declare(strict_types=1);

namespace App\Services;

class AdminService
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Get total user count
     */
    public function getTotalUsers(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM `user`");
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int) ($result['count'] ?? 0);
    }

    /**
     * Get user count by role
     */
    public function getUserCountByRole(string $role): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM `user` WHERE role = ?");
        $stmt->execute([$role]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int) ($result['count'] ?? 0);
    }

    /**
     * Get total events count
     */
    public function getTotalEvents(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM event");
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int) ($result['count'] ?? 0);
    }

    /**
     * Get published events count
     */
    public function getPublishedEvents(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM event WHERE is_published = 1");
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int) ($result['count'] ?? 0);
    }

    /**
     * Get total orders count
     */
    public function getTotalOrders(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM `order`");
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int) ($result['count'] ?? 0);
    }

    /**
     * Get total revenue from paid orders
     */
    public function getTotalRevenue(): float
    {
        $stmt = $this->db->query("SELECT COALESCE(SUM(total_price), 0) as total FROM `order` WHERE status = 'paid'");
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (float) ($result['total'] ?? 0);
    }

    /**
     * Get pending orders count
     */
    public function getPendingOrders(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM `order` WHERE status = 'pending'");
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int) ($result['count'] ?? 0);
    }

    /**
     * Get paid orders count
     */
    public function getPaidOrders(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM `order` WHERE status = 'paid'");
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int) ($result['count'] ?? 0);
    }

    /**
     * Get recent orders with user information
     */
    public function getRecentOrders(int $limit = 10): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                o.order_id,
                o.order_datetime,
                o.total_price,
                o.status,
                u.first_name,
                u.last_name,
                u.email
            FROM `order` o
            LEFT JOIN `user` u ON o.user_id = u.user_id
            ORDER BY o.order_datetime DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Get upcoming events
     */
    public function getUpcomingEvents(int $limit = 5): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                e.event_id,
                e.title,
                e.slug,
                e.start_datetime,
                e.end_datetime,
                l.name as location_name,
                e.is_published
            FROM event e
            LEFT JOIN location l ON e.location_id = l.location_id
            WHERE e.start_datetime > NOW()
            ORDER BY e.start_datetime ASC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Get recent registrations
     */
    public function getRecentUsers(int $limit = 5): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                user_id,
                first_name,
                last_name,
                email,
                role,
                created_at
            FROM `user`
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Get orders by status
     */
    public function getOrdersByStatus(string $status): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM `order` WHERE status = ?");
        $stmt->execute([$status]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int) ($result['count'] ?? 0);
    }

    /**
     * Get ticket sales summary
     */
    public function getTicketSalesSummary(): array
    {
        $stmt = $this->db->query("
            SELECT 
                COUNT(t.ticket_id) as total_tickets,
                SUM(CASE WHEN t.status = 'scanned' THEN 1 ELSE 0 END) as scanned_tickets,
                SUM(CASE WHEN t.status = 'valid' THEN 1 ELSE 0 END) as valid_tickets,
                SUM(CASE WHEN t.status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_tickets
            FROM ticket t
        ");
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [
            'total_tickets' => 0,
            'scanned_tickets' => 0,
            'valid_tickets' => 0,
            'cancelled_tickets' => 0
        ];
    }

    /**
     * Get revenue by date (last 7 days)
     */
    public function getRevenueByDate(int $days = 7): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                DATE(order_datetime) as date,
                COALESCE(SUM(total_price), 0) as revenue,
                COUNT(*) as order_count
            FROM `order`
            WHERE status = 'paid' AND order_datetime >= DATE_SUB(NOW(), INTERVAL ? DAY)
            GROUP BY DATE(order_datetime)
            ORDER BY date DESC
        ");
        $stmt->execute([$days]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }
}
