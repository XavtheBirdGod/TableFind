# Test Logboek - TableFind

Dit document bevat de resultaten van de functionele tests om de betrouwbaarheid van het systeem te waarborgen.

| ID | Testgeval | Type | Stappen | Verwacht Resultaat | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| TC01 | Dashboard Laden | Positief | Navigeren naar de admin-url. | Direct overzicht van alle reserveringen. | [X] |
| TC02 | Publieke Boeking | Positief | Gast vult het boekingsformulier in. | Reservering verschijnt in de database. | [X] |
| TC03 | Tafel Toevoegen | Positief | Nieuwe tafel 'T-10' aanmaken. | Tafel is direct zichtbaar in de lijst. | [X] |
| TC04 | Status Wijzigen | Positief | Tafelstatus naar 'occupied' zetten. | De status wordt direct bijgewerkt in de DB. | [X] |
| TC05 | Soft Delete Check | Positief | Een reservering verwijderen. | Record krijgt een `deleted_at` waarde. | [X] |
| TC06 | SQL Injectie Test | Negatief | Vreemde tekens invoeren in velden. | De `Security` class filtert de invoer correct. | [X] |
| TC07 | Dubbele Boeking | Negatief | Zelfde tafel op zelfde tijd boeken. | Systeem geeft een waarschuwing. | [X] |
| TC08 | Validatie Test | Negatief | Formulier verzenden zonder datum. | Gebruiker ziet een foutmelding. | [X] |

---
**Legenda:** [X] = Geslaagd | [ ] = Open | [F] = Gefaald