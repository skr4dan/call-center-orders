<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterOrdersRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderCreationService;
use App\Services\OrderQueryService;
use App\Services\OrderStatisticsService;

class OrdersController extends Controller
{
    public function __construct(
        private readonly OrderQueryService $orderQueryService,
        private readonly OrderCreationService $orderCreationService,
        private readonly OrderStatisticsService $orderStatisticsService
    ) {}

    public function create()
    {
        return view('orders.create');
    }

    public function store(StoreOrderRequest $request)
    {
        $this->orderCreationService->createOrder($request->validated());

        return redirect()->route('orders.create');
    }

    public function index(FilterOrdersRequest $request)
    {
        // Не вижу смысла кэшировать, учитывая, что менеджеров вряд-ли
        // много и им нужна как можно более актуальная информация
        $filters = $request->validated();
        $orders = $this->orderQueryService->getFilteredOrdersPaginated($filters);
        $stats = $this->orderStatisticsService->getOrderStats($filters);

        return view('orders.index', [
            'orders' => $orders,
            'stats' => $stats,
        ]);
    }
}
