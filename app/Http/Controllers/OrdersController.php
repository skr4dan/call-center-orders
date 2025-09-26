<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterOrdersRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;

class OrdersController extends Controller
{
    public function create()
    {
        return view('orders.create');
    }

    public function store(StoreOrderRequest $request)
    {
        Order::create($request->validated());

        return redirect()->route('orders.create');
    }

    public function index(FilterOrdersRequest $request)
    {
        // Не вижу смысла кэшировать, учитывая, что менеджеров вряд-ли
        // много и им нужна как можно более актуальная информация
        $query = Order::query()
            ->with('products')
            ->orderBy('date', 'desc')
            ->when($request->filled('search'), fn ($query) => $query
                // Можно сделать поиск более конкретным (вместо like) и повесить индексы итд, если нужна оптимизация
                // Но учитывается, что проект на sqlite, то не имеет смысла
                ->where('fio', 'like', '%'.$request->search.'%')
                ->orWhere('company', 'like', '%'.$request->search.'%')
                ->orWhere('phone', 'like', '%'.$request->search.'%')
                ->orWhereHas('products', fn ($query) => $query
                    ->where('name', 'like', '%'.$request->search.'%')
                )
            )
            ->when($request->filled('date_from'), fn ($query) => $query
                ->whereDate('created_at', '>=', $request->date_from)
            )
            ->when($request->filled('date_to'), fn ($query) => $query
                ->whereDate('created_at', '<=', $request->date_to)
            )
            ->when($request->filled('status'), fn ($query) => $query
                ->where('status', $request->status)
            );

        $statusCounts = (clone $query)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('orders.index', [
            'orders' => (clone $query)->paginate(50),
            'stats' => [
                'total' => (clone $query)->count(),
                'new' => $statusCounts[Order::STATUS_NEW] ?? 0,
                'in_progress' => $statusCounts[Order::STATUS_IN_PROGRESS] ?? 0,
                'done' => $statusCounts[Order::STATUS_DONE] ?? 0,
            ],
        ]);
    }
}
