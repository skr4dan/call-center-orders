<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fio' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->optional(0.6)->safeEmail(),
            'inn' => fake()->optional(0.7)->numerify('############'),
            'company' => fake()->optional(0.7)->passthrough($this->getFakeCompany()),
            'address' => fake()->optional(0.7)->address(),
            'status' => fake()->randomElement(array_keys(Order::STATUS_LABELS)),
        ];
    }

    public function withProducts(int $min = 1, int $max = 3): static
    {
        return $this->afterCreating(function (Order $order) use ($min, $max) {
            $productCount = fake()->numberBetween($min, $max);
            OrderProduct::factory($productCount)->create([
                'order_id' => $order->id,
            ]);
        });
    }

    protected function getFakeCompany(): string
    {
        return fake()->randomElement([
            'СтройГрад',
            'МонолитСтрой',
            'БетонСнаб',
            'ГородСтрой',
            'МосСтройИнвест',
            'ПромБетон',
            'АрмадаСтрой',
            'СтройПрофи',
            'СтройАльянс',
            'Мегаполис Девелопмент',
            'Городские Инженерные Системы',
            'МосГорПром',
            'ТехноДом',
            'Кирпичный Двор',
            'Бетонный Эксперт',
            'Архитектурная Мастерская №1',
            'СитиСтрой',
            'МастерФасад',
            'ЭкоСтройГрупп',
            'ЖБИ-Центр',
            'СтройПоставка',
            'Уютный Дом',
            'Городская Отделка',
            'МосИнжСтрой',
            'СтройТрансСнаб',
            'РемонтСервис',
            'СтройПанель',
            'Кровля и Фасад',
            'СтройТерминал',
            'Городская Служба Бетона',
            'Инженерные Решения',
        ]);
    }
}
