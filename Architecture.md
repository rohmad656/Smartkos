# Architecture.md

## 1. Technology Stack
- **Backend**: Laravel 13.35.0, PHP 8.3.x
- **Database**: MySQL 8.4 (via Laragon)
- **Frontend**: Blade templates, Tailwind CSS v4, Vite
- **JavaScript**: Alpine.js for interactivity, vanilla JS where needed
- **Build Tools**: Composer, npm, Vite
- **Testing**: PHPUnit, Playwright CLI

## 2. Application Structure
```
app/
  Http/
    Controllers/        # Route handlers
    Requests/           # Form request validation
    Middleware/         # Auth, role, middleware
  Models/               # Eloquent models
  Services/             # Complex business logic (booking, payment)
  Observers/            # Model events
database/
  factories/            # Model factories
  migrations/           # Schema migrations
  seeders/              # Development data
routes/
  web.php               # Route definitions
```

## 3. Database Relationships

### Users Table
- `id` (primary key)
- `name`, `email`, `email_verified_at`
- `password` (hashed)
- `role` (enum: 'super-admin', 'admin', 'user')
- `remember_token`
- `current_property_id` (nullable, FK to properties.id)

### Roles Table
- `id` (primary key)
- `name`, `display_name`, `description`
- `created_at`, `updated_at`

### Properties Table (optional multi-property support)
- `id` (primary key)
- `name`, `slug`, `address`, `latitude`, `longitude`
- `manager_id` (FK to users.id, admin managing this property)
- `status` (enum: 'active', 'inactive')

### Rooms Table
- `id` (primary key)
- `property_id` (FK to properties.id, nullable for single-property)
- `title`, `description`, `price_per_month`
- `room_size` (optional, sqm)
- `capacity` (int, max tenants)
- `amenities` (json: ['mandi_dalam', 'parkir', 'ac', ...])
- `status` (enum: 'available', 'unavailable', 'maintenance')
- `created_at`, `updated_at`

### Room Images Table
- `id` (primary key)
- `room_id` (FK to rooms.id)
- `url` (varchar)
- `is_primary` (boolean)
- `uploaded_at`

### Bookings Table
- `id` (primary key)
- `room_id` (FK to rooms.id)
- `user_id` (FK to users.id, the tenant)
- `status` (enum: 'pending', 'approved', 'rejected', 'cancelled', 'completed')
- `start_date` (date)
- `end_date` (date)
- `total_price` (decimal)
- `payment_status` (enum: 'unpaid', 'paid', 'partial', 'refunded')
- `notes` (text, nullable)
- `created_at`, `updated_at`

### Invoices Table
- `id` (primary key)
- `booking_id` (FK to bookings.id, nullable for non-booking invoices)
- `user_id` (FK to users.id)
- `amount` (decimal)
- `due_date` (date)
- `status` (enum: 'unpaid', 'paid', 'partial', 'overdue')
- `payment_method` (enum: 'midtrans', 'xendit', 'manual')
- `transaction_id` (varchar, nullable)
- `payment_date` (datetime, nullable)
- `notes` (text, nullable)
- `created_at`, `updated_at`

### Payments Table
- `id` (primary key)
- `invoice_id` (FK to invoices.id)
- `user_id` (FK to users.id)
- `amount` (decimal)
- `payment_method` (enum: 'midtrans', 'xendit', 'manual')
- `transaction_id` (varchar, unique)
- `gateway_response` (json, raw response from payment gateway)
- `verification_status` (enum: 'pending', 'verified', 'failed')
- `verification_attempts` (int, default 0)
- `verified_at` (datetime, nullable)
- `verified_by` (FK to users.id, nullable)
- `created_at`, `updated_at`

### Notifications Table (optional)
- `id` (primary key)
- `user_id` (FK to users.id)
- `type` (enum: 'booking', 'payment', 'system')
- `title`, `message`
- `is_read` (boolean, default false)
- `created_at`, `updated_at`

### Activity Logs Table
- `id` (primary key)
- `user_id` (FK to users.id, who performed action)
- `action` (varchar, e.g., 'booking_create', 'payment_verify')
- `entity_type` (varchar, e.g., 'booking', 'payment')
- `entity_id` (int)
- `ip_address` (varchar)
- `user_agent` (text)
- `created_at`

## 4. Authorization Model
- **Super Admin**: Full CRUD over all entities, system-wide management.
- **Admin**: CRUD over rooms, bookings, invoices, tenants within assigned property.
- **User**: CRUD over own bookings and invoices; view public rooms; cannot view other users' private data.

## 5. Booking Workflow
1. User submits booking request → status `pending`.
2. Admin reviews → approve (`approved`) or reject (`rejected`).
3. If approved → invoice generated → payment requested.
4. Payment verified → booking status `completed`.
5. Cancel -> status `cancelled`.

## 6. Billing / Payment Workflow
1. Invoice created → status `unpaid`.
2. Payment attempt via Midtrans/Xendit → `gateway_response` captured.
3. Server verifies payment status using gateway's documented verification mechanism.
4. If verified → `verification_status = 'verified'`, `payment_status = 'paid'`, `verified_at` set.
5. If failed → `verification_status = 'failed'`, `payment_status = 'unpaid'`.
6. Notification sent to user.

## 7. Technical Decisions
- **Migrations**: Laravel native, foreign keys, indexes on FK columns.
- **Polymorphic relations**: Not needed for core features; use explicit FKs.
- **Soft deletes**: On rooms/bookings only, not users.
- **Soft delete restoration**: Via model query.
- **Service classes**: For booking validation and payment processing.
- **Blade components**: For reusable UI (buttons, cards, tables, alerts).
- **Policies**: Per-model authorization.
- **Middleware**: `role`, `can`, `verified`.
- **CSRF**: Built-in via Laravel forms.
- **HTTPS**: Required for payment gateway callbacks.

## 8. Environment Configuration
- `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=smartkos`, `DB_USERNAME=root`, `DB_PASSWORD=`
- `VITE_APP_NAME=SmartKos`
- `MIDTRANS_SERVER_KEY=`, `MIDTRANS_CLIENT_KEY=` (sandbox, optional)
- `XENDIT_API_KEY=` (sandbox, optional)
- `CACHE_DRIVER=database`, `SESSION_DRIVER=database`, `QUEUE_DRIVER=database`
- `LOG_LEVEL=debug` (dev), `LOG_LEVEL=info` (prod)

## 9. Deployment Notes
- Production: use `.env.production`, set `APP_DEBUG=false`, `CACHE_DRIVER=file`, `VIEW_COMPILED_PATH`.
- Vite production build: `npm run build`.
- Queue workers optional for payment webhook processing.
- Always verify payment notifications on server, never trust browser-supplied status.