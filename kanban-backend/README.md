# Kanban Backend

This is a Laravel-based Kanban backend project designed to manage boards, cards, and tasks.

## Project Structure

```
kanban-backend
├── app
│   ├── Http
│   │   ├── Controllers
│   │   │   ├── BoardController.php
│   │   │   ├── CardController.php
│   │   │   └── TaskController.php
│   │   └── Middleware
│   ├── Models
│   │   ├── Board.php
│   │   ├── Card.php
│   │   └── Task.php
├── database
│   ├── migrations
│   │   ├── 2024_01_01_000000_create_boards_table.php
│   │   ├── 2024_01_01_000001_create_cards_table.php
│   │   └── 2024_01_01_000002_create_tasks_table.php
│   └── seeders
│       └── DatabaseSeeder.php
├── routes
│   └── api.php
├── config
│   └── app.php
├── composer.json
├── .env
└── README.md
```

## Installation

1. Clone the repository:
   ```
   git clone <repository-url>
   cd kanban-backend
   ```

2. Install dependencies:
   ```
   composer install
   ```

3. Set up your environment file:
   ```
   cp .env.example .env
   ```

4. Generate the application key:
   ```
   php artisan key:generate
   ```

5. Run migrations to set up the database:
   ```
   php artisan migrate
   ```

6. Seed the database with initial data:
   ```
   php artisan db:seed
   ```

## Usage

You can access the API endpoints defined in `routes/api.php` to manage boards, cards, and tasks. The controllers handle the logic for creating, updating, and deleting these entities.

## Contributing

Feel free to submit issues or pull requests for improvements or bug fixes. 

## License

This project is open-source and available under the [MIT License](LICENSE).