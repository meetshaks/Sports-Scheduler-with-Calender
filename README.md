# Sports Scheduler

Sports Scheduler is a web application designed to manage sports match schedules. It provides an admin interface for creating, updating, and deleting schedules, and a player interface for viewing schedules. The application uses PHP for backend logic, MySQL for data storage, and HTML for the frontend, with a simple and intuitive user interface.

## Features

- **Admin Login**: Secure login for administrators to access the admin panel.
- **Admin Panel**:
  - Add new schedules with date, time, group name, match count, and up to four players.
  - Update or delete existing schedules.
  - View schedules in a calendar format with navigation for previous and next weeks.
  - Validate player availability to prevent scheduling conflicts.
- **Player View**:
  - Displays a list of players and their scheduled matches.
  - Shows a calendar view of schedules with navigation.
  - Highlights recent updates for quick reference.
- **Backend Validation**:
  - Checks for duplicate player selections in a single schedule.
  - Ensures players are not scheduled for multiple matches at the same time.
- **Database**: Uses MySQL to store schedule data, including date, time, group name, match count, players, and timestamps.

## Project Structure

- `index.html`: Admin login page.
- `player.html`: Player interface for viewing schedules and players.
- `adminpanel.html`: Admin interface for managing schedules.
- `sports_scheduler.sql`: SQL script to create the `schedules` table in the MySQL database.
- `check_schedule.php`: Backend script to validate player availability for a given date and time.
- `login.php`: Handles admin login authentication.
- `schedules.php`: Manages CRUD operations (Create, Read, Update, Delete) for schedules.
- `config.php`: Database connection configuration.

## Prerequisites

- **Web Server**: Apache or any server supporting PHP (e.g., XAMPP, WAMP).
- **PHP**: Version 7.4 or higher.
- **MySQL**: Version 5.7 or higher.
- **Browser**: Modern web browser (Chrome, Firefox, etc.) for accessing the application.

## Installation

1. **Clone the Repository**
   ```bash
   git remote add origin https://github.com/meetshaks/Sports-Scheduler-with-Calender.git
   cd sports-scheduler
   ```

2. **Set Up the Database**
   - Create a MySQL database named `sports_scheduler`.
   - Import the `sports_scheduler.sql` file to create the `schedules` table:
     ```bash
     mysql -u root -p sports_scheduler < sports_scheduler.sql
     ```
   - Update `config.php` with your MySQL credentials (username and password).

3. **Configure the Web Server**
   - Place the project files in your web server's root directory (e.g., `htdocs` for XAMPP).
   - Ensure the server supports PHP and MySQL.

4. **Start the Server**
   - For XAMPP, start Apache and MySQL from the XAMPP control panel.
   - Alternatively, use a command like:
     ```bash
     php -S localhost:8000
     ```

5. **Access the Application**
   - Open a browser and navigate to `http://localhost/sports-scheduler`.
   - Use the admin login credentials (e.g., username: `shakil`, password: `1234`) to access the admin panel.

## Usage

### Admin Panel
1. Log in using the credentials defined in `login.php`.
2. In the admin panel, add a new schedule by selecting a date, time, group name (ASN, SNM, EXE), match count (2–5), and up to four players.
3. View, update, or delete existing schedules in the calendar view.
4. Use the "Previous" and "Next" buttons to navigate the calendar.

### Player View
1. Access the player page at `player.html`.
2. View the list of players and check their scheduled matches in the calendar.
3. Recent updates are displayed in a modal for quick reference.

### API Endpoints
- **GET `/schedules.php`**: Retrieve all schedules in JSON format.
- **POST `/schedules.php`**: Create a new schedule.
- **PUT `/schedules.php`**: Update an existing schedule.
- **DELETE `/schedules.php`**: Delete a schedule by ID.
- **POST `/check_schedule.php`**: Validate player availability for a given date and time.
- **POST `/login.php`**: Authenticate admin login.

## Notes
- The login system currently uses hardcoded credentials (`shakil`/`1234` or empty credentials) in `login.php`. For production, implement secure password hashing with `password_verify()`.
- The application assumes a MySQL database. Ensure the database is properly configured in `config.php`.
- The frontend uses basic HTML with implied JavaScript for dynamic interactions (e.g., calendar rendering, form submissions). Additional JavaScript files may be required for full functionality.
- CORS is enabled in `config.php` for cross-origin requests. Adjust for production environments as needed.

## Contributing
1. Fork the repository.
2. Create a new branch (`git checkout -b feature/your-feature`).
3. Commit your changes (`git commit -m 'Add your feature'`).
4. Push to the branch (`git push origin feature/your-feature`).
5. Open a pull request.

## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact
For issues or suggestions, please open an issue on the GitHub repository or contact [your-email@example.com].
