## Installation Steps

1. **Clone the Project**:  
   First, clone the project from GitHub to your local machine.

2. **Configure the Environment**:  
   Create `.env` using `.env.example` file and add your configurations. Make sure to set up your database connection and other necessary settings. Also change configurations on env.testing file with testing environemnt configs as well.

3. **Run the Following Commands**:

   ```bash
   composer update
   php artisan key:generate
   php artisan migrate
   php artisan db:seed --class=RoleSeeder
   php artisan db:seed --class=UserSeeder
   php artisan db:seed --class=RoleSeeder --env=testing
   php artisan db:seed --class=UserSeeder --env=testing

4. **Run the Following Commands**:

   Use following credentials to login to the system
      Admin (Username : superadmin / Password : abc123)
      User 1 (Username : user1 / Password : abc123)
      User 2 (Username : user2 / Password : abc123)