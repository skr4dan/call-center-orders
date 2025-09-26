<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderRepository;

class OrderStatisticsService
{
    public function __construct(
        private readonly OrderRepository $orderRepository
    ) {}

    public function getOrderStats(array $filters = []): array
    {
        $query = $this->orderRepository->getFilteredOrdersForStats($filters);
        $statusCounts = $this->orderRepository->getOrderStatusCounts($query);

        return [
            'total' => $query->count(),
            'new' => $statusCounts[Order::STATUS_NEW] ?? 0,
            'in_progress' => $statusCounts[Order::STATUS_IN_PROGRESS] ?? 0,
            'done' => $statusCounts[Order::STATUS_DONE] ?? 0,
        ];
    }
}
