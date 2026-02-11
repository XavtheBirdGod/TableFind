# Test Logboek - MVC Validatie

| ID | Testgeval | Component | Verwacht Resultaat | Status |
| :--- | :--- | :--- | :--- | :--- |
| TC01 | Data Routing | Controller | Router stuurt `/reservations` correct naar `index()`. | [X] |
| TC02 | Model Integrity | Repository | Data wordt correct opgeslagen via de Repository-laag. | [X] |
| TC03 | View Rendering | View | Variabelen uit de Controller worden correct getoond in de HTML. | [X] |
| TC04 | Soft Delete Flow | Model | `deleted_at` wordt geüpdatet zonder de rij fysiek te wissen. | [X] |