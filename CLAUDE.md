# Proud Innovations — Kassa Continuïteitsdienst Offerte Applicatie

## Project overzicht
Laravel 12 + Livewire 3 + Alpine.js + Tailwind CSS + MySQL + DomPDF
Webapplicatie voor het aanmaken van offertes voor de Kassa Continuïteitsdienst.

## Gebruikersrollen
- admin → /beheer/dashboard
- verkoper → /verkoper/dashboard  
- samensteller → /verkoper/dashboard (mag alleen concept aanmaken, niet versturen)

## Kleurschema
Primaire kleur sidebar en PDF: #1B3A6B (donkerblauw)

## Belangrijke bestanden
- PDF template: resources/views/pdf/quote.blade.php
- PDF service: app/Services/QuotePdfService.php
- Afhankelijkheidslogica: app/Livewire/Admin/Dependencies/Index.php
- Offerte configurator: app/Livewire/Verkoper/Quotes/Create.php
- Instellingen: app/Models/Setting.php (key/value structuur)

## Bekende issues en afspraken
- Sidebar navigatie: staat als pure HTML in layouts/app-admin.blade.php 
  en layouts/app-verkoper.blade.php — NOOIT in een Livewire component plaatsen
- wire:navigate wordt gebruikt in de sidebar — Livewire componenten 
  moeten x-on:livewire-navigate-end.window="$wire.$refresh()" bevatten
  als ze wire:model.live gebruiken op dropdowns
- PDF marges: @page { margin-top: 35mm; margin-bottom: 25mm; 
  margin-left: 20mm; margin-right: 20mm; }
- Geldigheid offerte: ophalen uit Setting waar key='quote_validity_days', 
  NIET hardcoded op 30 dagen
- Logo in PDF: altijd als base64 embedden via QuotePdfService
- Datumnotatie overal: dd-mm-yyyy (Nederlands)
- Bedragen: number_format($bedrag, 2, ',', '.') met €-teken ervoor

## Routes naamgeving
Admin routes: beheer.dashboard, beheer.producten.*, beheer.afhankelijkheden.*, 
              beheer.gebruikers.*, beheer.activiteit.*, beheer.instellingen.*,
              beheer.offertes.*
Verkoper routes: verkoper.dashboard, verkoper.offertes.*
Overig: taken.index, profiel.edit, notificaties.index

## PDF structuur (quote.blade.php)
Artikelen 1 t/m 11 exact zoals in de Overeenkomst Kassa Continuïteitsdienst.
Statische teksten ophalen uit settings tabel waar mogelijk.
Koptekst op elke pagina: logo links, offertenummer+datum rechts.
Voettekst op elke pagina: bedrijfsinfo links, paginanummer rechts.
Page-break-before: always op artikelen 6, 9, 10, 11.

## Wat er nog gebouwd moet worden (todo)
1. PDF verbeteren: marges, koptekst met logo, paginanummering, 
   pagina-indeling zonder halflege pagina's
2. Versiegeschiedenis van offertes (snapshot bij elke opslag)
3. Samensteller rol (derde rol naast admin en verkoper)
4. Digitaal ondertekenen (fase 2 — klant ondertekent via link)
