<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class OrdersWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_access_orders_index(): void
    {
        $manager = $this->createManagerUser();
        $order = Order::factory()->create();

        $this
            ->actingAs($manager)
            ->get('/orders')
            ->assertOk()
            ->assertSee('Таблица заказов')
            ->assertSee($order->fio);
    }

    #[TestWith([Order::STATUS_NEW])]
    #[TestWith([Order::STATUS_IN_PROGRESS])]
    #[TestWith([Order::STATUS_DONE])]
    public function test_manager_can_filter_orders_by_status(string $statusValue): void
    {
        $manager = $this->createManagerUser();
        Order::factory()->create([
            'status' => Order::STATUS_NEW,
            'fio' => 'Иван Иванов',
        ]);
        Order::factory()->create([
            'status' => Order::STATUS_IN_PROGRESS,
            'fio' => 'Петр Петров',
        ]);
        Order::factory()->create([
            'status' => Order::STATUS_DONE,
            'fio' => 'Андрей Андреев',
        ]);

        $this
            ->actingAs($manager)
            ->get('/orders?status='.$statusValue)
            ->assertOk()
            ->assertSee(Order::STATUS_LABELS[$statusValue]."\n</td>", false)
            ->tap(fn ($response) => collect(Order::STATUS_LABELS)
                ->except($statusValue)
                ->each(fn ($label) => $response
                    ->assertDontSee($label."\n</td>", false)
                )
            );
    }

    public function test_manager_can_view_order_statistics(): void
    {
        $manager = $this->createManagerUser();

        collect([
            Order::factory()->create(['status' => Order::STATUS_NEW]),
            Order::factory()->create(['status' => Order::STATUS_NEW]),
            Order::factory()->create(['status' => Order::STATUS_NEW]),
            Order::factory()->create(['status' => Order::STATUS_IN_PROGRESS]),
            Order::factory()->create(['status' => Order::STATUS_IN_PROGRESS]),
        ]);

        $this
            ->actingAs($manager)
            ->get('/orders')
            ->assertOk()
            ->assertSee('Статистика');
    }

    public function test_operator_can_access_create_order_page(): void
    {
        $operator = $this->createOperatorUser();

        $this
            ->actingAs($operator)
            ->get('/orders/create')
            ->assertOk()
            ->assertSee('Создание заказа')
            ->assertSee('ФИО')
            ->assertSee('Телефон')
            ->assertSee('Почта');
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

        $this
            ->actingAs($operator)
            ->post('/orders', $orderData)
            ->assertRedirect('/orders/create');

        $this->assertDatabaseHas('orders', [
            'fio' => 'John Doe',
            'phone' => '+7 (999) 123-45-67',
        ]);

        $this->assertDatabaseHas('order_products', [
            'name' => 'Test Product',
            'quantity' => 5,
        ]);
    }

    public function test_guest_user_is_redirected_to_login_from_orders(): void
    {
        $this
            ->get('/orders')
            ->assertRedirect('/login');
    }

    public function test_guest_user_is_redirected_to_login_from_orders_store(): void
    {
        $this
            ->post('/orders')
            ->assertRedirect('/login');
    }

    public function test_guest_user_is_redirected_to_login_from_orders_create(): void
    {
        $this
            ->get('/orders/create')
            ->assertRedirect('/login');
    }

    public function test_operator_cannot_access_orders_index(): void
    {
        $user = $this->createOperatorUser();

        $this
            ->actingAs($user)
            ->get('/orders')
            ->assertForbidden();
    }

    public function test_manager_cannot_access_orders_create(): void
    {
        $user = $this->createManagerUser();

        $this
            ->actingAs($user)
            ->get('/orders/create')
            ->assertForbidden();
    }

    public function test_manager_cannot_access_orders_store(): void
    {
        $user = $this->createManagerUser();

        $this
            ->actingAs($user)
            ->post('/orders')
            ->assertForbidden();
    }

    private function createManagerUser(): User
    {
        $user = User::factory()->create();
        $user->role()->associate(Role::firstWhere('name', Role::MANAGER));
        $user->save();

        return $user;
    }

    private function createOperatorUser(): User
    {
        $user = User::factory()->create();
        $user->role()->associate(Role::firstWhere('name', Role::OPERATOR));
        $user->save();

        return $user;
    }
}
