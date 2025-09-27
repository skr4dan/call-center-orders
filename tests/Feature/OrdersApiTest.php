<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrdersApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_orders(): void
    {
        Order::factory()->count(3)->create();

        $response = $this
            ->getJson('/api/orders')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'fio',
                        'phone',
                        'email',
                        'status',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_can_filter_orders_by_status(): void
    {
        Order::factory()->create(['status' => Order::STATUS_NEW]);
        Order::factory()->create(['status' => Order::STATUS_IN_PROGRESS]);
        Order::factory()->create(['status' => Order::STATUS_NEW]);

        $this
            ->getJson('/api/orders?status='.Order::STATUS_NEW)
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.*.status', array_fill(0, 2, Order::STATUS_NEW));
    }

    public function test_can_create_order(): void
    {
        $orderData = [
            'fio' => 'John Doe',
            'phone' => '+7 (999) 123-45-67',
            'email' => 'john@example.com',
            'inn' => '1234567890',
            'company' => 'Test Company',
            'address' => 'Test Address 123',
            'products' => [
                [
                    'name' => 'Test Product',
                    'quantity' => 5,
                    'unit' => OrderProduct::UNIT_PIECES,
                ],
            ],
        ];

        $response = $this->postJson('/api/orders', $orderData);

        $response->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'fio',
                    'phone',
                    'email',
                    'status',
                    'products',
                ],
            ])
            ->assertJsonPath('data.fio', 'John Doe');

        $this->assertDatabaseHas('orders', [
            'fio' => 'John Doe',
            'phone' => '+7 (999) 123-45-67',
        ]);
    }


    public function test_can_get_order_statistics(): void
    {
        $orders = collect([
            Order::factory()->create(['status' => Order::STATUS_NEW]),
            Order::factory()->create(['status' => Order::STATUS_NEW]),
            Order::factory()->create(['status' => Order::STATUS_NEW]),
            Order::factory()->create(['status' => Order::STATUS_IN_PROGRESS]),
            Order::factory()->create(['status' => Order::STATUS_IN_PROGRESS]),
        ]);

        $response = $this->getJson('/api/orders/stats');

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'total' => 5,
                    'new' => 3,
                    'in_progress' => 2,
                    'done' => 0,
                ],
            ]);
    }


}
