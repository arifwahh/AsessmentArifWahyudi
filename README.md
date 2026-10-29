# Courier API

A small Laravel 12 JSON API for managing courier master data. It has no user interface; all endpoints return JSON.

## Requirements

- PHP 8.2 or newer
- Composer 2
- SQLite (default) or MySQL 8

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

The API is then available at `http://127.0.0.1:8000/api/couriers`.

## Database

The default `.env.example` uses SQLite (`database/database.sqlite`). To use MySQL, create a database and update `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=courier_api
DB_USERNAME=root
DB_PASSWORD=
```

Then run the migrations and seeder:

```bash
php artisan migrate --seed
```

Alternatively, import the ready-to-use MySQL dump:

```bash
mysql -u root -p courier_api < database/couriers.sql
```

`database/couriers.sql` contains the `couriers` table and 25 sample rows, including `Budiono Hadi Agung`.

## Data model

| Column | Type | Notes |
| --- | --- | --- |
| `id` | bigint unsigned | Primary key |
| `name` | varchar(255) | Courier name |
| `email` | varchar(255) | Unique |
| `phone` | varchar(20) | Unique, Indonesian phone format |
| `level` | tinyint unsigned | 1 to 5 |
| `vehicle_type` | varchar(20) | `motorcycle`, `car`, `van`, `truck` |
| `vehicle_plate` | varchar(15) | Unique, e.g. `B 1234 XYZ` |
| `city` | varchar(100), nullable | |
| `address` | text, nullable | |
| `is_active` | boolean | Default `true` |
| `created_at` | timestamp | Registration date |
| `updated_at` | timestamp | |

## Endpoints

| Method | URI | Description |
| --- | --- | --- |
| GET | `/api/couriers` | Paginated list with search, filter, and sort |
| POST | `/api/couriers` | Create a courier |
| GET | `/api/couriers/{id}` | Show a courier |
| PUT/PATCH | `/api/couriers/{id}` | Update a courier (PATCH accepts partial data) |
| DELETE | `/api/couriers/{id}` | Delete a courier |

### Index query parameters

| Parameter | Example | Description |
| --- | --- | --- |
| `search` | `?search=budi+agung` | Every word must match the name. Matches `Budiono Hadi Agung`. |
| `level` | `?level=2,3` | Only levels 2 and 3. Valid levels are 1-5. |
| `sort` | `?sort=-created_at` | Sort by `name` (default), `level`, or `created_at`. Prefix with `-` for descending. |
| `per_page` | `?per_page=25` | Items per page. Default 15, minimum 1, maximum 100. |

Filters can be combined:

```bash
curl "http://127.0.0.1:8000/api/couriers?search=budi+agung&level=2,3&sort=-created_at&per_page=10"
```

Pagination metadata uses Laravel's standard paginator format (`data`, `current_page`, `last_page`, `per_page`, `total`, `links`, and so on). Pagination links keep the active filters.

### Create a courier

```bash
curl -X POST http://127.0.0.1:8000/api/couriers \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Budi Santoso",
    "email": "budi.santoso@example.com",
    "phone": "081234567890",
    "level": 2,
    "vehicle_type": "motorcycle",
    "vehicle_plate": "B 1234 XYZ",
    "city": "Bandung",
    "address": "Jl. Merdeka No. 1",
    "is_active": true
  }'
```

### Update a courier

```bash
curl -X PATCH http://127.0.0.1:8000/api/couriers/1 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"level": 3, "city": "Jakarta"}'
```

### Delete a courier

```bash
curl -X DELETE http://127.0.0.1:8000/api/couriers/1 \
  -H "Accept: application/json"
```

## Validation

| Field | Rules |
| --- | --- |
| `name` | Required, string, max 255 |
| `email` | Required, valid email, max 255, unique |
| `phone` | Required, max 20, phone format, unique |
| `level` | Required, integer, between 1 and 5 |
| `vehicle_type` | Required, one of `motorcycle`, `car`, `van`, `truck` |
| `vehicle_plate` | Required, max 15, plate format, unique |
| `city` | Optional, string, max 100 |
| `address` | Optional, string, max 500 |
| `is_active` | Optional, boolean |

Unique rules ignore the current courier when updating. Invalid input returns HTTP 422 with a JSON `errors` object.

## Testing

```bash
php artisan test
```

The feature tests use an in-memory SQLite database and cover pagination, default and date sorting, multi-word search, level filtering, show, create, update, delete, validation, and 404 responses.

## Code style

```bash
./vendor/bin/pint --test
```

## Implementation notes

- `Courier` model contains reusable `search`, `filterLevel`, and `sort` scopes.
- `StoreCourierRequest` and `UpdateCourierRequest` keep validation out of the controller.
- `CourierController` is an API resource controller; there are no Blade views.
- The `sort`, `level`, and `per_page` inputs are bounded/whitelisted to avoid unexpected queries.
