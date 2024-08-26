# Blemish.lk Inventory Management System

## Introduction
This application is a web-based Inventory Management System designed for a clothing store. It allows users to manage products, suppliers, sales, and more, with role-based access control. The system can be set up on Windows using XAMPP or on a Linux server running Apache. 🧥📦

## Prerequisites

### Windows (Using XAMPP) 🖥️
- **XAMPP**: Ensure XAMPP is installed on your system. You can download it from [apachefriends.org](https://www.apachefriends.org).
- **Web Browser**: Any modern web browser like Chrome, Firefox, or Edge.

### Linux (Using Apache Server) 🐧
- **Apache**: Ensure Apache server is installed.
- **PHP**: Version 7.0 or higher.
- **MySQL**: Version 5.6 or higher.
- **Web Browser**: Any modern web browser like Chrome or Firefox.

## Setup Instructions

### Windows (Using XAMPP) 🛠️

1. **Download XAMPP**:
   - Install XAMPP from [apachefriends.org](https://www.apachefriends.org).

2. **Download the Project**:
   - Clone the repository or download and extract the project files into the `htdocs` directory inside your XAMPP installation folder (usually located at `C:\xampp\htdocs`). 📂

3. **Start XAMPP**:
   - Open the XAMPP Control Panel and start the Apache and MySQL modules. 🚀

4. **Create the Database**:
   - Open your browser and go to `http://localhost/phpmyadmin`.
   - Create a new database (e.g., `blemish_inventory`). 🗄️
   - Import the `database-query.sql` file located in the `sql` folder of the project into this new database.

5. **Configure the Application**:
   - Open the `dbcon.php` file and update the database settings to match your local environment (e.g., username, password, and database name). 🔧

6. **Run the Application**:
   - Open your web browser and go to `http://localhost/your_project_folder`.
   - Log in using the credentials provided (or create a new user if registration is enabled). 🔑

Enjoy managing your inventory with ease! 🎉


### Linux (Using Apache Server) 🐧

1. **Install Required Packages**:
   - Open a terminal and install Apache, PHP, and MySQL with the following commands:  
     ```bash
     sudo apt-get update
     sudo apt-get install apache2 php libapache2-mod-php php-mysql mysql-server
     ```

2. **Download the Project**:
   - Clone the repository or download and extract the project files into the `/var/www/html` directory:  
     ```bash
     sudo cp -r your_project_folder /var/www/html/
     ```

3. **Create the Database**:
   - Log in to MySQL:  
     ```bash
     mysql -u root -p
     ```
   - Create a new database (e.g., `blemish_inventory`):  
     ```sql
     CREATE DATABASE blemish_inventory;
     ```
   - Import the SQL file:  
     ```bash
     mysql -u root -p blemish_inventory < /path/to/database-query.sql
     ```

4. **Configure the Application**:
   - Navigate to the `config` directory in the project folder.
   - Edit `dbcon.php` with your MySQL credentials to match your environment (e.g., username, password, and database name).

5. **Set Permissions**:
   - Set the appropriate permissions for the project folder:  
     ```bash
     sudo chown -R www-data:www-data /var/www/html/your_project_folder
     sudo chmod -R 755 /var/www/html/your_project_folder
     ```

6. **Restart Apache**:
   - Restart Apache to apply the changes:  
     ```bash
     sudo systemctl restart apache2
     ```

7. **Run the Application**:
   - Open your web browser and navigate to `http://localhost/your_project_folder`.
   - Log in using the credentials provided (or create a new user if registration is enabled). 🔑

Enjoy managing your inventory with ease! 🎉


