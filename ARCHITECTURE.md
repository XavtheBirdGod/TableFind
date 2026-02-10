# Architecture Documentation

This document describes the high-level software architecture of the project and the reasoning behind key design decisions.

## 1. Architectural Pattern: MVC
This project follows the **Model-View-Controller (MVC)** design pattern to achieve a clear **Separation of Concerns (SoC)**.

### Responsibilities
* **Model:** * Manages the data, logic, and business rules of the application.
    * It is independent of the UI and handles data persistence/retrieval.
* **View:** * Responsible for the visual representation of the data.
    * It receives data from the Controller and presents it to the user without containing business logic.
* **Controller:** * Acts as the intermediary.
    * It processes user input, interacts with the Model, and selects the appropriate View to render.

---

## 2. Component Responsibilities
| Component | Responsibility | Why? |
| :--- | :--- | :--- |
| **Domain Models** | Business logic & validation | Ensures data integrity at the core level. |
| **Controllers** | Request handling & Orchestration | Keeps the entry points lean by delegating logic to the Models. |
| **Views / Templates** | Presentation Layer | Simplifies UI updates without breaking the underlying logic. |
| **Services / Data Access** | Database interaction | Abstracts complexity away from the Controller to keep it "thin." |

---

## 3. Design Choices & Principles

### Choice 1: [e.g., Use of the Repository Pattern]
* **Explanation:** We implemented a Repository layer between the Controller and the Database.
* **Course Concepts:** This increases **Loose Coupling** and improves **Testability**, as we can easily swap the real database for a "mock" during unit testing.

### Choice 2: [e.g., Heavy Model vs. Thin Controller]
* **Explanation:** Business logic is kept strictly within the Model layer rather than the Controller.
* **Course Concepts:** This follows the **Information Expert** principle from GRASP, ensuring that the object that has the data is also the one performing operations on it, leading to higher **Cohesion**.