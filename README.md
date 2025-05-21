
Built by https://www.blackbox.ai

---

# Griya Kolektiva

## Project Overview
Griya Kolektiva is a web application designed for managing a hotel booking system. The platform allows users to search for available rooms, book them, and facilitate payments. The project features an admin dashboard for managing room availability and booking statistics.

## Installation
To set up the Griya Kolektiva project locally, follow these steps:
1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/griya-kolektiva.git
   ```
2. **Set up a local server:**
   - You can use a local server environment like XAMPP, MAMP, or any other PHP-enabled server setup.
3. **Create a database:**
   - Open phpMyAdmin or your preferred MySQL tool.
   - Create a new database named `griya_kolektiva`.
   - (Optional: Import any necessary SQL files if provided in the repository.)
4. **Configure database connection:**
   - Modify the connection settings in the relevant PHP files (like `process_booking.php` and `calendar.php`) to reflect your local database configuration.
5. **Access the application:**
   - Run your local server and navigate to `http://localhost/griya-kolektiva` in your web browser.

## Usage
1. **Home Page:** Users can view featured rooms and search for available rooms by entering check-in and check-out dates.
2. **Room Listing:** Users can see a detailed list of available rooms and their descriptions.
3. **Booking:** Users can fill out a form to book a room based on their selection.
4. **Payment:** Once booked, users are directed to a payment page with instructions for completing their booking.
5. **Admin Panel:** Admins can log in to manage bookings and update room availability, and view financial reports.

## Features
- **Room Search & Bookings:** Users can search for rooms based on availability and book them.
- **Responsive Design:** The application is designed to be mobile-friendly.
- **Admin Dashboard:** For managing room availability, viewing booking statistics, and generating reports.
- **Payment Instructions:** Users receive payment instructions after booking their stay.

## Dependencies
The project utilizes the following dependencies specified in the `package.json` (if applicable):

- No external libraries are indicated in the provided files. Make sure to include any frameworks or libraries used that are not explicitly mentioned.

## Project Structure
```
griya-kolektiva/
├── index.html         # Home page
├── rooms.html         # List of rooms with booking options
├── booking.html       # Booking form for room reservation
├── payment.html       # Payment details and instructions
├── admin_login.php    # Admin login page
├── admin_dashboard.php # Admin dashboard for managing bookings and rooms
├── manage_rooms.php    # Interface for admins to manage room details
├── financial_reports.php # Admin page for viewing financial statistics
├── calendar.php       # Calendar view of all bookings
├── process_booking.php # Handles booking logic and updates
├── payment.php        # Displays payment details to users
├── styles.css         # Styles for the application
└── scripts.js         # JavaScript for frontend interactivity
```

## Authors
- Developed by the Griya Kolektiva team.

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.