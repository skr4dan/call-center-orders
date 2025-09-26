# Call Center Orders

Система управления заказами для колл-центра

## Примечание по поводу ТЗ
Из дизайна странички создания заказов непонятно, каким образом должны обрабатываться дубликаты и уже существующие клиенты
Поэтому API-ручка, связанная с получением клиентов по номеру телефона не реализована.

## Системные требования

- **PHP:** 8.2 или выше
- **Composer:** для установки PHP зависимостей
- **Node.js:** 18.0 или выше
- **npm:**

## Установка
```bash
git clone https://github.com/skr4dan/call-center-orders \
&& cd call-center-orders \
&& cp .env.example .env \
&& php artisan key:generate \
&& touch database/database.sqlite \
&& php artisan migrate --seed \
&& npm install \
&& npm update \
&& npm run build \
&& php artisan serve
```

**Готовые учетные записи для входа:**
- **Менеджер:** `manager@example.com` / `password`
- **Оператор:** `operator@example.com` / `password`

## Веб-страницы
- `/login` - вход
- `/` - перенаправление по роли
- `/orders` - просмотр списка заказов (менеджер)
- `/orders/create` - Создание заказа (оператор)

## API
**Base URL:** `/api`

### GET /api/orders
**Примеры запросов:**
- `?search=иванов` (поиск по ФИО, компании, телефону и названию продукта)
- `?date_from=2025-09-01&date_to=2025-09-30` (фильтр по дате)
- `?status=in_progress` (заказы в работе)

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "fio": "Виль Фёдорович Мамонтов",
      "phone": "(812) 688-41-40",
      "email": null,
      "inn": null,
      "company": "СтройАльянс",
      "address": "181529, Калининградская область, город Мытищи, наб. Ломоносова, 69",
      "status": "new",
      "status_label": "Новый",
      "products": [
        {
          "id": 1,
          "name": "Перемычка 2ПБ 13-1",
          "quantity": 6,
          "unit": "pcs",
          "unit_label": "Штуки",
          "short_unit_label": "шт.",
          "created_at": "2025-09-26T08:54:18.000000Z",
          "updated_at": "2025-09-26T08:54:18.000000Z"
        },
        {
          "id": 2,
          "name": "Пропитка для кирпича",
          "quantity": 8,
          "unit": "l",
          "unit_label": "Литры",
          "short_unit_label": "л.",
          "created_at": "2025-09-26T08:54:18.000000Z",
          "updated_at": "2025-09-26T08:54:18.000000Z"
        }
      ],
      "created_at": "2025-09-26T08:54:18.000000Z",
      "updated_at": "2025-09-26T08:54:18.000000Z"
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/orders?page=1",
    "last": "http://localhost:8000/api/orders?page=3",
    "prev": null,
    "next": "http://localhost:8000/api/orders?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 3,
    "per_page": 50,
    "to": 50,
    "total": 150
  }
}
```

### POST /api/orders
**Пример создания заказа:**
```json
{
  "fio": "Иванов Иван Иванович",
  "phone": "+7(999)999-99-99",
  "email": "ivanov@example.com",
  "inn": "123456789012",
  "company": "ООО СтройМатериалы",
  "address": "г. Москва, ул. Ленина, д. 15, кв. 42",
  "products": [
    {
      "name": "Кирпич керамический",
      "quantity": 1000,
      "unit": "pcs"
    },
    {
      "name": "Цемент М500",
      "quantity": 25,
      "unit": "kg"
    },
    {
      "name": "Песок строительный",
      "quantity": 500,
      "unit": "kg"
    }
  ]
}
```

### GET /api/orders/stats
**Примеры запросов:**
- `?status=new` (статистика только новых заказов)
- `?date_from=2025-09-01&date_to=2025-09-30` (статистика за сентябрь)

**Response:**
```json
{
  "data": {
    "total": 50,
    "new": 57,
    "in_progress": 43,
    "done": 50
  }
}
```
