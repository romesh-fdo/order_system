## Installation Steps

1. **Clone the Project**:  
   First, clone the project from GitHub to your local machine.

2. **Configure the Environment**:  
   Create `.env` using `.env.example` file and add your configurations. Make sure to set up your database connection and other necessary settings.

3. **Run the Following Commands**:

   ```bash
   composer update
   php artisan key:generate
   php artisan migrate
   php artisan db:seed --class=StatusSeeder
   php artisan db:seed --class=RoleSeeder
   php artisan db:seed --class=UserSeeder
