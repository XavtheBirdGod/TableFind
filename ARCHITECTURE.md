# Architectuur Documentatie

## 1. Architectural Pattern: MVC
Ondanks de minimalistische insteek behouden we het **Model-View-Controller (MVC)** patroon. Dit garandeert dat de presentatielaag (Views) volledig gescheiden blijft van de database-interacties (Repositories).

## 2. Design Choices & Principes

### Keuze 1: Accountless Management
Er is bewust gekozen voor een systeem zonder gebruikersaccounts. Dit minimaliseert de complexiteit van sessiebeheer en beveiligingsprotocollen, wat perfect aansluit bij een direct beheersysteem voor personeel.

### Keuze 2: Repository Pattern
Alle database-logica is geïsoleerd in de `ReservationsRepository` en `TablesRepository`. Dit maakt het systeem modulair en klaar voor toekomstige uitbreidingen zonder de kernstructuur te verstoren.

### Keuze 3: Lean Architecture
Door het weglaten van autorisatie-lagen blijven de controllers "lean". De focus ligt puur op het verwerken van boekingsdata en het beheren van de status van de tafels.