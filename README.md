# Domain-Driven Design (DDD) PHP 8.2 Ecommerce System

Welcome to the Domain-Driven Design (DDD) PHP 8.2 Ecommerce System! This project aims to provide a robust and scalable Restful API-based ecommerce system using PHP 8.2, all without relying on Laravel or any other third-party project. This README will guide you through the setup, architecture, and usage of the system.

## Introduction

The DDD PHP 8.2 Ecommerce System is designed with a focus on Domain-Driven Design principles. It provides a solid foundation for building a flexible and maintainable ecommerce solution, emphasizing separation of concerns and modularity.

## Features

- Domain-Driven Design: The system adheres to DDD principles, helping you create a clear and structured architecture for your ecommerce application.
- Restful API: The API is designed to be RESTful, enabling easy integration with various client applications.
- PHP 8.2: Utilize the latest features and enhancements provided by PHP 8.2 for improved performance and maintainability.
- Modular Design: The architecture promotes modularization, making it easier to add, update, or replace specific components of the system.
- Custom ORM: Implement a simple Object-Relational Mapping (ORM) tailored to the needs of the ecommerce domain.

## Architecture

The architecture of the DDD PHP 8.2 Ecommerce System follows a layered approach, separating concerns and promoting a clear division of responsibilities. The main layers are:

- **Presentation Layer:** Handles user interaction, including handling HTTP requests and responses. It exposes the Restful API endpoints.
- **Application Layer:** Orchestrates the interaction between the Presentation and Domain layers. Contains application services and use cases.
- **Domain Layer:** Contains the core business logic and domain entities. This layer is the heart of the application and should be independent of other layers.
- **Infrastructure Layer:** Provides implementations for external dependencies such as databases, HTTP clients, and third-party integrations.

## Screenshots

![screenshot1](/screenshots/screenshot%20(1).png)
![screenshot2](/screenshots/screenshot%20(2).png)
![screenshot3](/screenshots/screenshot%20(3).png)

## Routes

### Product

- `GET /products`: Show all products.
- `GET /product/{id}`: Show the product with specified id.

### Cart

- `GET /cart`: Return all products in users cart.
- `POST /cart/add`: Add a product to users cart.

### Checkout

- `GET /checkout/review`: Show user cart and get user address for placing order.
- `POST /checkout`: Place a new order and bill user.
- `GET /checkout/result/{status}`: Show order acceptance status.

### Payment

- `GET /payment/pay/{orderId}`: Pay the bill.
- `POST /payment/callback`: Verify bill payment.

## Getting Started

**Prerequisites**

- PHP 8.2 or higher
- Composer
- Mysql

**Installation**

- Clone this repository: `git clone https://github.com/BaseMax/EcommercePHPDDD.git`
- Go to project directory: `cd EcommercePHPDDD`
- Install dependencies: `composer install`
- Setup mysql database tables: you can either use sql code below or use sql backup `database.sql`
- Edit `.env` file with your own values
- (optionaly) Seed the database: `cd app/Infrastructure/Database/ && php MysqlSeeder.php`
- Run the server: `cd public/ && php -S localhost:8000`
- App is running on `http://localhost:8000`

## Mysql Tables SQL Code

**products**

```sql
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price INT NOT NULL
);
```

**orders**

```sql
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    address TEXT NOT NULL,
    total_price INT NOT NULL,
    status VARCHAR(255) NOT NULL
);
```

**order_products**

```sql
CREATE TABLE IF NOT EXISTS order_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

**payments**

```sql
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    idpay_id VARCHAR(255) NOT NULL,
    link VARCHAR(255) NOT NULL,
    amount INT NOT NULL,
    status INT NOT NULL,
    track_id INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);
```

## API Documentation

For detailed API documentation, refer to the API Documentation file. It provides information about available endpoints, request/response formats, and example usage.

## Contributing

Contributions to this project are welcome! Feel free to fork the repository, make changes, and submit pull requests. Please follow the established coding guidelines and keep the DDD principles in mind.

## License

This project is licensed under the GPL-3.0 License.

Copyright 2023, Max Base
