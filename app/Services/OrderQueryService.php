<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderQueryService
{
    public function __construct(
        private readonly OrderRepository $orderRepository
    ) {}

    public function getFilteredOrdersPaginated(array $filters = []): LengthAwarePaginator
    {
        return $this->orderRepository->getFilteredOrdersPaginated($filters);
    }
}
