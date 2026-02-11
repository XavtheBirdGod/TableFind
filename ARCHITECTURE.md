# Architectuur Documentatie - MVC Diepgang

## 1. Het MVC Patroon in Detail
In dit project is het **Model-View-Controller (MVC)** patroon de ruggengraat. Het scheidt de data-afhandeling van de gebruikersinterface, wat essentieel is voor de schaalbaarheid van **TableFind**.

[Image of a detailed MVC sequence diagram showing data flow from user request to database and back]

### De Componenten:
* **Model (Data & Logica)**: 
    * Vertegenwoordigd door klassen in `app/Models`. 
    * Het beheert de staat van de reserveringen en tafels.
    * **Repositories**: We gebruiken het Repository-patroon om de Model-logica te ondersteunen. De `ReservationsRepository` fungeert als een abstractielaag die de SQL-complexiteit verbergt voor de controller.
* **View (Presentatie)**: 
    * Gelegen in `app/Views`. 
    * Gebruikt pure PHP-templates om data te renderen naar HTML. 
    * De Views zijn "dom"; ze bevatten geen SQL-queries of complexe bedrijfslogica, enkel logica voor weergave (zoals loops en conditionals).
* **Controller (Orkestratie)**: 
    * Gelegen in `app/Controllers`. 
    * De `ReservationsController` ontvangt de HTTP-verzoeken van de `Router`, roept de juiste Repository-methode aan en stuurt de resulterende data naar de View.

## 2. Request Lifecycle (De Reis van een Verzoek)
1.  **Entry Point**: Alle verzoeken komen binnen via `public/index.php`.
2.  **Routing**: De `Router` analyseert de URL en koppelt deze aan een Controller-methode.
3.  **Action**: de Controller communiceert met de **Repository** om data op te halen of op te slaan.
4.  **Response**: De Controller laadt de **View** via de `View::render()` methode en stuurt de volledige HTML terug naar de browser.

## 3. Waarom deze MVC-structuur?
* **Onderhoudbaarheid**: Wijzigingen in de database-structuur (Model) vereisen geen aanpassingen in de interface (View).
* **Testbaarheid**: Door de logica in Repositories te plaatsen, kunnen we database-functies onafhankelijk testen.
* **Clean Code**: Controllers blijven klein en overzichtelijk (Thin Controllers).