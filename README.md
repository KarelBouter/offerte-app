# Offerte Tool — Proud Innovations B.V.

Interne offerte-applicatie voor de **Kassa Continuïteitsdienst** van Proud Innovations B.V.
Gebouwd met Laravel 13, Livewire 3, Tailwind CSS en DomPDF.

---

## Vereisten

| Tool       | Versie    |
|------------|-----------|
| PHP        | ≥ 8.2     |
| Composer   | ≥ 2.x     |
| Node.js    | ≥ 20.x    |
| SQLite     | dev       |
| MySQL      | ≥ 8.0 (prod) |

---

## Lokale installatie

```bash
# 1. Clone de repository
git clone https://github.com/KarelBouter/offerte-app.git
cd offerte-app

# 2. PHP-afhankelijkheden
composer install

# 3. Omgevingsvariabelen
cp .env.example .env
# Stel DB_CONNECTION=sqlite in voor dev
php artisan key:generate

# 4. Database
touch database/database.sqlite
php artisan migrate --seed

# 5. Frontend
npm install
npm run dev

# 6. Lokale server
php artisan serve
```

De applicatie draait nu op `http://localhost:8000`.

**Standaard admin-account** (na seeder):
- E-mail: `admin@proudinnovations.nl`
- Wachtwoord: `password`

---

## Rollen

| Rol       | Toegang                                                        |
|-----------|----------------------------------------------------------------|
| `admin`   | Volledig beheer: producten, gebruikers, instellingen, logs     |
| `verkoper`| Offertes aanmaken, bekijken en versturen                       |

---

## Functionaliteit

- **Offertes**: aanmaken via 3-staps wizard, PDF-generatie, statusbeheer
- **Producten & afhankelijkheden**: admin beheert catalogus en dependency-regels
- **Gebruikersbeheer**: admin beheert accounts en rollen
- **E-mail naar klant**: offerte-link met onderteken-token (14 dagen geldig)
- **Activiteitenlog**: audit trail van alle acties in het systeem
- **Automatische vervaldatum**: dagelijkse cron zet verlopen offertes op "verlopen"

---

## Productie deploy

```bash
# Kopieer en vul .env in
cp .env.example .env
nano .env   # Stel DB, MAIL en APP_URL in

# Genereer sleutel
php artisan key:generate

# Deploy uitvoeren
bash deploy.sh
```

Het `deploy.sh` script voert uit: git pull → composer → npm build → migraties → cache → maintenance mode aan/uit.

**Cron instellen** (éénmalig op server):
```bash
* * * * * cd /pad/naar/offerte-app && php artisan schedule:run >> /dev/null 2>&1
```

---

## Mailconfiguratie

Stel in `.env` in:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=noreply@proudinnovations.nl
MAIL_PASSWORD=geheim
MAIL_FROM_ADDRESS=noreply@proudinnovations.nl
MAIL_FROM_NAME="Proud Innovations B.V."
```

Voor development werkt `MAIL_MAILER=log` (mail wordt naar `storage/logs/laravel.log` geschreven).

---

## Sessie & beveiliging

- Sessies worden opgeslagen in de database (`SESSION_DRIVER=database`)
- Rate limiting: max. 5 inlogpogingen per minuut per IP
- Policies: `QuotePolicy` en `ProductPolicy` bewaken toegang op modelniveau
- `TrimStrings` middleware actief op alle requests

---

## Projectstructuur (kern)

```
app/
  Console/Commands/ExpireQuotes.php      — verlopen offertes markeren
  Http/Controllers/
    PublicQuoteController.php            — publieke offerte-link
    QuotePdfController.php               — PDF download
  Livewire/
    Admin/                               — beheer (producten, users, settings, logs)
    Verkoper/                            — offertes (index, create, show)
    Profile/                             — profielbeheer
  Mail/QuoteClientMail.php               — e-mail naar klant
  Models/                                — Eloquent-modellen
  Policies/                              — QuotePolicy, ProductPolicy
  Services/
    ActivityLogService.php               — audit logging
    QuotePdfService.php                  — PDF-generatie
database/
  migrations/                            — alle migraties
  seeders/DatabaseSeeder.php             — admin-account seeder
resources/views/
  layouts/                               — app-admin, app-verkoper, public
  livewire/                              — component-views
  public/                                — publieke offerte-views
  pdf/                                   — DomPDF-template
routes/
  web.php                                — alle routes
  console.php                            — scheduled commands
```
