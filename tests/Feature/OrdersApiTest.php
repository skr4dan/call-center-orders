<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrdersApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_list_orders(): void
    {
        $manager = $this->createManagerUser();
        Order::factory()->count(3)->create();

        $response = $this
            ->actingAs($manager)
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

    public function test_manager_can_filter_orders_by_status(): void
    {
        $manager = $this->createManagerUser();
        Order::factory()->create(['status' => Order::STATUS_NEW]);
        Order::factory()->create(['status' => Order::STATUS_IN_PROGRESS]);
        Order::factory()->create(['status' => Order::STATUS_NEW]);

        $this
            ->actingAs($manager)
            ->getJson('/api/orders?status='.Order::STATUS_NEW)
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.*.status', array_fill(0, 2, Order::STATUS_NEW));
    }

    public function test_operator_can_create_order(): void
    {
        $operator = $this->createOperatorUser();

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

        $response = $this->actingAs($operator)
            ->postJson('/api/orders', $orderData);

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

    public function test_operator_cannot_list_orders(): void
    {
        $this
            ->actingAs($this->createOperatorUser())
            ->getJson('/api/orders')
            ->assertForbidden();
    }

    public function test_manager_can_get_order_statistics(): void
    {
        $manager = $this->createManagerUser();

        $orders = collect([
            Order::factory()->create(['status' => Order::STATUS_NEW]),
            Order::factory()->create(['status' => Order::STATUS_NEW]),
            Order::factory()->create(['status' => Order::STATUS_NEW]),
            Order::factory()->create(['status' => Order::STATUS_IN_PROGRESS]),
            Order::factory()->create(['status' => Order::STATUS_IN_PROGRESS]),
        ]);

        $response = $this->actingAs($manager)
            ->getJson('/api/orders/stats');

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

    public function test_operator_cannot_get_order_statistics(): void
    {
        $this
            ->actingAs($this->createOperatorUser())
            ->getJson('/api/orders/stats')
            ->assertForbidden();
    }

    public function test_unauthorized_user_cannot_access_orders(): void
    {
        $this
            ->actingAs(User::factory()->create())
            ->getJson('/api/orders')
            ->assertForbidden();
    }

    public function test_unauthorized_user_cannot_create_orders(): void
    {
        $this
            ->actingAs(User::factory()->create())
            ->postJson('/api/orders', [])
            ->assertForbidden();
    }

    private function createManagerUser(): User
    {
        $user = User::factory()->create();
        $managerRole = Role::where('name', Role::MANAGER)->first();
        $user->role()->associate($managerRole);
        $user->save();

        return $user;
    }

    private function createOperatorUser(): User
    {
        $user = User::factory()->create();
        $operatorRole = Role::where('name', Role::OPERATOR)->first();
        $user->role()->associate($operatorRole);
        $user->save();

        return $user;
    }
}
