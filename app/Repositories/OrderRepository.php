<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class OrderRepository
{
    public function getBaseOrderQuery(): Builder
    {
        return Order::query()->with('products');
    }

    public function applyFilters(Builder $query, array $filters = []): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function ($query, $search) {
                // Можно сделать поиск более конкретным (вместо like) и повесить индексы итд, если нужна оптимизация
                // Но учитывается, что проект на sqlite, то не имеет смысла
                $query->where(function ($q) use ($search) {
                    $q->where('fio', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('products', function ($productQuery) use ($search) {
                            $productQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($filters['date_from'] ?? null, function ($query, $dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($filters['date_to'] ?? null, function ($query, $dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            })
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            });
    }

    public function getFilteredOrdersPaginated(array $filters = []): LengthAwarePaginator
    {
        $query = $this->getBaseOrderQuery()
            ->orderByDesc('created_at');

        $query = $this->applyFilters($query, $filters);

        return $query->paginate(50);
    }

    public function getFilteredOrdersCount(array $filters = []): int
    {
        return $this->applyFilters(Order::query(), Arr::except($filters, ['status']))->count();
    }

    public function getOrderCountByStatuses(array $filters = []): array
    {
        return $this
            ->applyFilters(Order::query(), $filters)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    public function saveOrderWithProducts(array $data): Order
    {
        $order = Order::create([
            'fio' => $data['fio'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'inn' => $data['inn'] ?? null,
            'company' => $data['company'] ?? null,
            'address' => $data['address'] ?? null,
            'status' => $data['status'] ?? Order::STATUS_NEW,
        ]);

        if (isset($data['products']) && is_array($data['products'])) {
            foreach ($data['products'] as $productData) {
                $name = Arr::get($productData, 'name');
                $quantity = Arr::get($productData, 'quantity');

                if (blank($name) || ! is_numeric($quantity) || (int) $quantity <= 0) {
                    continue;
                }

                $order->products()->create([
                    'name' => $name,
                    'quantity' => (int) $quantity,
                    'unit' => Arr::get($productData, 'unit', OrderProduct::UNIT_PIECES),
                ]);
            }
        }

        return $order->load('products');
    }
}
