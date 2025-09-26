<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateOrderRequest;
use App\Http\Requests\Api\FilterOrdersRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderCreationService;
use App\Services\OrderQueryService;
use App\Services\OrderStatisticsService;
use Illuminate\Http\JsonResponse;

class OrdersController extends Controller
{
    public function __construct(
        private readonly OrderQueryService $orderQueryService,
        private readonly OrderCreationService $orderCreationService,
        private readonly OrderStatisticsService $orderStatisticsService
    ) {}

    public function index(FilterOrdersRequest $request)
    {
        $filters = $request->validated();
        $orders = $this->orderQueryService->getFilteredOrdersPaginated($filters);

        return OrderResource::collection($orders);
    }

    public function store(CreateOrderRequest $request): JsonResponse
    {
        $order = $this->orderCreationService->createOrder($request->validated());

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(201);
    }

    public function stats(FilterOrdersRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $stats = $this->orderStatisticsService->getOrderStats($filters);

        return response()->json([
            'data' => $stats,
        ]);
    }
}
