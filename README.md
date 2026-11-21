# Pothik: Bangladesh Tourism Information and Tour Planning Portal

A dynamic, database-driven web application for exploring Bangladesh and planning trips.
Built for the **CIS324 Web Engineering** course at Daffodil International University.

- **Student:** Apu Paul (ID 241-16-005, Section 19-A)
- **Stack:** Plain PHP 8, MySQL (PDO), Tailwind CSS (CDN), vanilla JavaScript
- **Live demo:** _add your deployed URL here_

## Features

**For visitors**
- Landing page with a picture slideshow, then register / login

**For logged-in users**
- **Destinations:** 10 top spots, category filter, search, pagination, detail page with photos
- **Reviews:** 1 to 5 star rating and comment (one review per user per destination)
- **Trip Planner:** create, edit and delete trips, add stops, compare budget with estimate
- **Budget Planner:** enter a budget and see which destinations fit it, with a full cost breakdown
- **Trip Cost Estimator:** pick start city, destination, days, travelers, transport and travel style; edit every amount and watch the total update live
- **Directory:** hotels, restaurants and hospitals near each destination
- **Emergency Hub:** national helplines and local hospitals
- **Packing Checklist:** saved in the browser

**For admins**
- Admin panel to add, edit and delete destinations (role checked on the server)

**API**
- Read-only JSON endpoint: `api/destinations.php` (filter by `id`, `category`, `q`, with `page` and `limit`)

## Security

- PDO prepared statements everywhere (SQL injection)
- Output escaped with `htmlspecialchars` (XSS)
- CSRF token on every POST form, checked on the server
- Passwords hashed with `password_hash` / `password_verify`
- Session fixation protection (`session_regenerate_id`), `HttpOnly`, `SameSite` and `Secure` cookies
- Login attempt limit (5 failed attempts, 5 minute lock)
- Logout is a POST request with a CSRF token
- Role-based access control: admin pages re-check the role in the database and return 403 otherwise
- Ownership checks (`WHERE id = ? AND user_id = ?`) on trips
- Input validation with whitelists and integer casting

## Project structure

```
admin/            admin panel (destinations CRUD)
api/              JSON API
app/
  controllers/    Trip, Info, Budget, Estimate controllers
  models/         Trip, Destination, City, TransportMode, ...
  services/       CostCalculator (shared cost rules)
  views/          page templates
assets/images/    destination photos (credits in CREDITS.txt)
config/           config.example.php (copy to config.php)
database/         pothik_db.sql (structure), seed.sql (data)
includes/         header, footer, csrf, auth guard, helpers
```

## How to run locally (XAMPP)

1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache** and **MySQL**.
2. Put this folder at `C:\xampp\htdocs\pothik` (the folder name must be `pothik`, links use `/pothik/`).
3. Open `http://localhost/phpmyadmin` and create a database named `pothik_db`
   (collation `utf8mb4_general_ci`).
4. Select `pothik_db`, then **Import** `database/pothik_db.sql`, then **Import** `database/seed.sql`.
5. Copy `config/config.example.php` to `config/config.php` and edit the details if needed
   (XAMPP default: user `root`, empty password).
6. Open `http://localhost/pothik/`.
7. Register an account. To make it an admin, run in phpMyAdmin:
   ```sql
   UPDATE users SET role = 'admin' WHERE email = 'your_email';
   ```
   then log out and log in again.

## How costs are estimated

Costs are **estimates**, not live prices. Distance uses the Haversine formula x 1.3 road factor.
Transport uses published bus and train fare per km. Hotel, food and local travel are shares
(45% / 35% / 20%) of each destination's average daily cost, adjusted by travel style, room sharing
and peak season. Extras and emergency buffer are 10% each. Fixed ship fares (Saint Martin) are stored in the database.

## Data sources and credits

- Photos: Wikimedia Commons (see `assets/images/CREDITS.txt`)
- Hotels, restaurants and hospitals: Google Maps listings (verify before real use)
- Bus and train fares: BRTA notices, Prothom Alo, The Financial Express, TBS News
- Saint Martin ship fare: Prothom Alo (Nov 2026)

## AI disclosure

AI assistance (Claude) was used for guidance and code help with the teacher's permission.
Full details are in Annex A of the project report.

## Limitations

- Prices are stored averages and need manual updates
- Boat, ferry and permit costs are included only where a fixed fare is stored
- Login attempt limit is session based

## Author

Apu Paul, Department of Computing and Information System, Daffodil International University.