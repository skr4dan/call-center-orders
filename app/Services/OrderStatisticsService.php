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
        $total = $this->orderRepository->getFilteredOrdersCount($filters);
        $statuses = $this->orderRepository->getOrderCountByStatuses($filters);

        return [
            'total' => $total,
            'new' => $statuses[Order::STATUS_NEW] ?? 0,
            'in_progress' => $statuses[Order::STATUS_IN_PROGRESS] ?? 0,
            'done' => $statuses[Order::STATUS_DONE] ?? 0,
        ];
    }
}
