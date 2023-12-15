
# Todo App Setup Instructions

## Backend

### 1. Create .env file

Create a `.env` file in the Laravel backend directory (`./laravel`) and add the following:

    POSTMARK_TOKEN=your_postmark_token

### 2. Serve the backend
Run the following command to start the Laravel development server:

    composer install
    php artisan serve

### 3. Run migrations
Apply the database migrations with:

    php artisan migrate

### 4. Run the seeders
Seed the database with test data using:

    php artisan db:seed

### 5. Run tests

Execute tests (note: running tests will clear the database):

    php artisan test

## Frontend
### 1. Serve the Frontend
Navigate to the Vue.js frontend directory (`./vue`) and run:

    npm i
    npm run serve

### 2. Login
Use the following credentials to log in:

-   Username/Email: yoram@classid.be
-   Password: secret
