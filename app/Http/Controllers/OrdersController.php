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

        return view('orders.index', [
            'orders' => (clone $query)->paginate(50),
            'stats' => [
                'total' => (clone $query)->count(),
                'new' => (clone $query)
                    ->where('status', Order::STATUS_NEW)->count(),
                'in_progress' => (clone $query)
                    ->where('status', Order::STATUS_IN_PROGRESS)->count(),
                'done' => (clone $query)
                    ->where('status', Order::STATUS_DONE)->count(),
            ],
        ]);
    }
}
