<?php

namespace App\Repositories\Interfaces;

interface DashboardRepositoryInterface
{
    /**
     * Get revenue and order counts summary (today, week, month, new, pending, cancelled, completed).
     *
     * @return array
     */
    public function getRevenueAndOrderSummary(): array;

    /**
     * Get daily revenue and order count for the last N days.
     *
     * @param int $days
     * @return array
     */
    public function getDailyRevenueChart(int $days = 30): array;

    /**
     * Get monthly revenue and order count for a given year.
     *
     * @param int $year
     * @return array
     */
    public function getMonthlyRevenueChart(int $year): array;

    /**
     * Get top selling products with quantity sold and revenue.
     *
     * @param int $limit
     * @return array
     */
    public function getTopSellingProducts(int $limit = 5): array;

    /**
     * Get low stock products alert list.
     *
     * @param int $threshold
     * @param int $limit
     * @return array
     */
    public function getLowStockProducts(int $threshold = 5, int $limit = 10): array;

    /**
     * Get overdue pending orders alert list (> X hours).
     *
     * @param int $hours
     * @param int $limit
     * @return array
     */
    public function getOverduePendingOrders(int $hours = 24, int $limit = 10): array;

    /**
     * Get recent orders with customer details.
     *
     * @param int $limit
     * @return array
     */
    public function getRecentOrders(int $limit = 5): array;
}
