# Team Plan - Tabel Reserveringssysteem (TableFind)

## Domain
Het project **TableFind** is een gestroomlijnde beheerinterface voor restaurantreserveringen. Het systeem is ontworpen voor directe toegang en gericht op operationele snelheid in een vertrouwde omgeving, zonder de overhead van gebruikersaccounts.

## Entities
1. **Table**: De fysieke tafels van het restaurant (tafelnummer, capaciteit, status).
2. **Reservation**: De kernentiteit die klantgegevens koppelt aan specifieke tafels en tijdstippen.

## Database Structure
Het systeem maakt gebruik van een minimalistisch en efficiënt schema:
* **reservations**: Slaat alle boekingsdetails op, inclusief status en een optionele Foreign Key naar de tafels.
* **tables**: Beheert de configuratie en beschikbaarheid van de zitplaatsen.



## Task Division
* **Jihad (Backend Lead)**: Ontwerp van de data-architectuur, implementatie van de Repositories en de functionele logica voor het reserveringsbeheer.
* **Xanthe (Frontend & UI)**: Realisatie van de visuele interface voor zowel de publieke boekingspagina als het administratieve dashboard.

## Agreements
* **Directe Toegang**: Het systeem is geoptimaliseerd voor intern gebruik, waarbij snelheid belangrijker is dan toegangscontrole.
* **Data Validatie**: Alle invoer wordt via de controllers streng gecontroleerd om de integriteit van de database te waarborgen.