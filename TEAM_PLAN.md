# Team Plan - Tabel Reserveringssysteem

## Project Doelstelling
Het bouwen van een robuust, accountloos beheerplatform voor restauranttafels met een strikte scheiding van verantwoordelijkheden volgens de MVC-standaarden.

## Entities & Verhoudingen
* **Tafels (1)** <---> **Reserveringen (N)**: Een tafel kan meerdere reserveringen hebben over verschillende tijden.
* **Status Management**: Gebruik van ENUM-waarden in de database om consistentie te waarborgen binnen het Model.

## Task Division (MVC Focus)
* **Jihad**: Verantwoordelijk voor de Model-laag (Repositories & Database) en de Controller-logica.
* **Xanthe**: Verantwoordelijk voor de View-laag (HTML/CSS templates) en de integratie van UI-feedback.