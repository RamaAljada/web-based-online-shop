Project Overview
This project is a full-featured web-based online shop developed using HTML, CSS, JavaScript, PHP, and MySQL, with phpMyAdmin for database management.
It follows a client-server architecture, where the frontend handles user interaction and presentation, while the backend manages business logic, database operations, and data security.

Main goals of the project include:

User account management

Product categorization and browsing

Shopping cart operations

Order placement with payment handling and tax calculation

Dynamic content driven by the database

System Architecture & Technologies

The system follows a client-server architecture, divided into three layers: Frontend, Backend, and Database.

Each layer has a specific role and uses appropriate technologies to ensure a secure and dynamic user experience.

2.1 Frontend

Role:

Display pages and product content

Handle user interactions like clicks, form submissions, and quantity changes

Send user input to the backend for database processing

Technologies Used:

HTML: Structure pages, forms, product cards, categories, and define inputs and buttons

CSS: Layout, colors, fonts, responsive design, and hover effects for a better user experience

JavaScript: Adds interactivity, including quantity control, validation messages, alerts, and dynamic cart updates without page reloads

2.2 Backend

Role:

Process forms and user inputs

Authenticate users and manage permissions

Perform CRUD operations for users, categories, and items

Handle shopping cart logic and order processing with tax calculations

Ensure secure communication with the database and link frontend actions to backend operations

Technology Used: PHP

Example Operations: User login verification, cart management, order processing, account updates, and deletion

2.3 Database

Role:

Store persistent data like users, product categories, and items

Maintain data integrity across all tables and layers

Technology Used: MySQL, managed through phpMyAdmin

Database Design

The database has three main tables: shop, categories, and items

3.1 shop Table

Stores user accounts

Columns: user_id (PK), username, phone, country_code, email (fixed), password (hashed)

Role: Link user actions to their account and ensure secure authentication

3.2 categories Table

Stores product categories

Columns: id (PK), name, image

Role: Organize products for easier navigation and filtering

3.3 items Table

Stores products

Columns: id (PK), category_id (FK references categories.id), name, price, image

SQL Queries and Database Operations

All CRUD operations are implemented through PHP:

Create: Register users, add items to the cart

Read: Fetch categories, display items by category, load user profiles

Update: Edit user information (except email), update cart quantities

Delete: Remove items from cart, delete user accounts

All queries ensure data integrity and secure, accurate data management

Shopping Cart System

Users can add, increase/decrease, or remove items

The cart is stored in sessions or temporary database tables

Maintains consistency until checkout

Calculates totals including taxes
