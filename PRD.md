# PRD.md

## Project Name
SmartKos — Platform Cari Kos Pintar untuk Mahasiswa & Perantau Indonesia

## Objective
Bangun platform web berbasis Laravel yang memudahkan pengguna menemukan, book, dan manajemen kamar kos dengan data terpercaya, peta real-time, dan integrasi pembayaran.

## Target Users
- **Primary**: Mahasiswa (18-27 tahun) yang mencari kos harian/bulanan.
- **Secondary**: Perantau (pelajar, karyawan muda) yang butuh akomodasi sementara.
- **Admin**: Manajer kos (individu atau tim) yang mengelola kamar, pemesanan, dan keuangan.

## Functional Requirements
1. **Landing Page**
   - Tampilkan hero dengan CTA "Find Your Room".
   - Tampilkan daftar kamar dengan foto, harga, rating, dan indikator ketersediaan.
   - Fitur filter kampus/area, budget, dan amenities (jalan kaki, mandi dalam, dsb).
   - Integrasi peta interaktif dengan pin lokasi dan filter jarak jalan kaki.

2. **User Authentication**
   - Registrasi & login dengan email/password.
   - Otentikasi terverifikasi (email confirmation opsional).
   - Reset password via email.

3. **Role-Based Access Control**
   - **Super Admin**: Dashboard system-wide, manajemen user, laporan, audit log.
   - **Admin**: Manajemen kamar, pemesanan, tagihan, booking, payment status, reporting.
   - **User**: Dashboard pribadi, booking request, history, detail kamar, tagihan, payment history.

4. **Booking & Payment**
   - Sistem booking dengan status: `pending`, `approved`, `rejected`, `cancelled`, `completed`.
   - Integrasi payment gateway (Midtrans/Xendit) di mode sandbox.
   - Tagihan otomatis berdasarkan status booking.
   - Notifikasi pembayaran sukses/gagal.

5. **Dashboard**
   - **Super Admin**: Ringkasan statistik (kamar, tenant, booking, payment), manajemen user, audit log.
   - **Admin**: Manajemen kamar, booking, tagihan, tenant, laporan harian.
   - **User**: Dashboard pribadi, detail kamar, booking request, history, payment status.

6. **Responsive Design**
   - Responsif untuk desktop, tablet, dan mobile.
   - Prioritas konten, hamburger menu di mobile, grid layout adaptif.

7. **Security & Compliance**
   - CSRF, XSS, SQL injection protection.
   - Password hashing (bcrypt).
   - Rate limiting pada login/register.
   - Validasi server-side pada semua input.

## Non-Functional Requirements
- **Performance**: Load time < 2s pada connection 4G.
- **Accessibility**: WCAG 2.1 level AA compliance.
- **Maintainability**: Clean codebase, Laravel conventions, modular structure.
- **Scalability**: Support 100+ kamar dan 10k+ user.

## User Stories
- **As a user**, I want to browse available rooms with photos and prices so I can choose the best option.
- **As a user**, I want to register and login securely so I can access my account.
- **As a user**, I want to submit a booking request and track its status so I know when I’m confirmed.
- **As an admin**, I want to manage room availability and pricing so I can maximize occupancy.
- **As a super admin**, I want to view system-wide reports so I can monitor business performance.

## Acceptance Criteria
- Landing page, registration, login, dashboard, booking flow, payment tracking, and responsive UI are fully functional.
- All role-based access controls enforce correct permissions.
- Automated tests pass for core workflows (registration, login, booking, payment).
- UI matches design system (colors, typography, spacing, components).
- Database schema normalized with proper relationships and constraints.

## Scope Boundaries
- **In Scope**: User registration/login, room browsing, booking, payment tracking, admin dashboards, responsive UI, basic reporting.
- **Out of Scope**: Multi-property management (beyond single property), complex accounting, mobile app (web-first), advanced analytics.

## Design Guidelines
- Follow frontend-design skill: distinctive visual identity, no generic SaaS templates.
- Color palette: --paper (#FFF9EB), --ink (#1B1E1A), --teak (#3D2C17), --teal (#0E7C6B), --mustard (#E8B44A), --line (#D9D6CF).
- Typography: Fraunces (display), DM Sans (body).
- Layout: Editorial split (kiri teks / kanan peta), 3-column card grid, left-aligned text.
- Components: Tailwind CSS v4, Blade components, Alpine.js for interactivity.

## Dependencies
- Laravel 13.35.0
- PHP 8.3.x
- MySQL 8.4
- Node.js 26.x
- Tailwind CSS v4
- Vite
- Laravel Vite plugin

## Non-Functional Constraints
- No real credentials in Git.
- No database reset in production.
- No third-party auth packages (use Laravel built-in).
- No AI-generated design templates (custom design only).