<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderRepository;

class OrderCreationService
{
    public function __construct(
        private readonly OrderRepository $orderRepository
    ) {}

    public function createOrder(array $orderData): Order
    {
        return $this->orderRepository->saveOrderWithProducts($orderData);
    }
}
