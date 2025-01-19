# Laravel Starter Kit - Authentication and CRUD Repository

A ready-to-use Laravel starter kit featuring authentication,and a base CRUD repository. This package is designed to save time and effort by providing a robust foundation for any Laravel-based API application.

---

## Features


### **Authentication**
- JWT-based authentication for secure API token management.

### **Base CRUD Repository**
- Centralized CRUD repository pattern to minimize code redundancy and enhance maintainability.

### **Image Uploads**
- Handles image uploads and storage with automatic naming and folder organization.
- Supports image uploads for all CRUD features.

### **API Ready**
- Pre-configured routes and controller logic to quickly set up API endpoints.

---

## Installation

### 1. Clone the Repository
```bash
git clone https://github.com/your-repository-url/laravel-starter-kit.git
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Configure Environment
- Copy `.env.example` to `.env`
- Update database credentials and other environment variables in `.env`.

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Run Migrations
```bash
php artisan migrate
```

### 5. Seed Database (Optional)
```bash
php artisan db:seed
```

### 6. Serve the Application
```bash
php artisan serve
```

---

## Usage

### **Controllers**
- Example `ProductController` is provided with full CRUD functionality.
- Extend the `CrudRepository` to add logic for other models.

### **Routes**
Pre-configured routes for authentication and product management:

```php
// routes/api.php
Route::apiResource('products', ProductController::class);
```

### **Requests**
Custom request classes for validation:
- `StoreProductRequest`
- `UpdateProductRequest`

### **Environment Variables**
- Set pagination count with `PAGINATE` in `.env`.

---

## Folder Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   └── ProductController.php
│   ├── Requests/
│   │   ├── StoreProductRequest.php
│   │   └── UpdateProductRequest.php
├── Models/
│   └── Product.php
├── Repositories/
│   ├── BaseRepository.php
│   └── CrudRepository.php
```

---

## Contributing
Contributions are welcome! If you find any bugs or have suggestions for improvements, feel free to submit a pull request or open an issue.

---

## License
This project is licensed under the MIT License. See the `LICENSE` file for details.

---

## Author
[Your Name](https://github.com/your-github-username)

---

## Future Improvements
- Support for more complex query filters.
- Additional role and permission .
- Additional pre-built components for common use cases.
