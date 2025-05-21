<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$dbname = "griya_kolektiva";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fetch bookings for calendar display
$bookings_result = $conn->query("SELECT b.id, r.name AS room_name, b.check_in, b.check_out, b.status
                                FROM bookings b
                                JOIN rooms r ON b.room_id = r.id
                                ORDER BY b.check_in ASC");

$bookings = [];
while ($row = $bookings_result->fetch_assoc()) {
    $bookings[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kalender Pemesanan - Griya Kolektiva</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
        table.calendar {
            width: 100%;
            border-collapse: collapse;
        }
        table.calendar th, table.calendar td {
            border: 1px solid #555;
            padding: 0.5rem;
            text-align: center;
            vertical-align: top;
            height: 100px;
        }
        .booked {
            background-color: #444;
            color: #fff;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <h1>Kalender Pemesanan</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="manage_rooms.php">Manajemen Kamar</a></li>
                <li><a href="financial_reports.php">Laporan Keuangan</a></li>
                <li><a href="calendar.php" aria-current="page">Kalender</a></li>
                <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="calendar-section">
            <h2>Kalender Pemesanan Bulan Ini</h2>
            <?php
            // Generate calendar for current month
            $year = date('Y');
            $month = date('m');
            $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
            $daysInMonth = date('t', $firstDayOfMonth);
            $dayOfWeek = date('N', $firstDayOfMonth); // 1 (Mon) to 7 (Sun)

            echo "<table class='calendar'>";
            echo "<tr><th>Sen</th><th>Sel</th><th>Rab</th><th>Kam</th><th>Jum</th><th>Sab</th><th>Min</th></tr><tr>";

            // Blank cells before first day
            for ($i = 1; $i < $dayOfWeek; $i++) {
                echo "<td></td>";
            }

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $dateStr = sprintf("%04d-%02d-%02d", $year, $month, $day);
                $bookedRooms = [];

                foreach ($bookings as $booking) {
                    if ($booking['check_in'] <= $dateStr && $booking['check_out'] > $dateStr) {
                        $bookedRooms[] = $booking['room_name'] . " (" . $booking['status'] . ")";
                    }
                }

                $class = count($bookedRooms) > 0 ? "booked" : "";
                echo "<td class='$class'><strong>$day</strong><br/>";
                if ($bookedRooms) {
                    echo implode("<br/>", $bookedRooms);
                }
                echo "</td>";

                if (($day + $dayOfWeek - 1) % 7 == 0) {
                    echo "</tr><tr>";
                }
            }

            // Blank cells after last day
            $lastDayOfWeek = ($daysInMonth + $dayOfWeek - 1) % 7;
            if ($lastDayOfWeek != 0) {
                for ($i = $lastDayOfWeek; $i < 7; $i++) {
                    echo "<td></td>";
                }
            }

            echo "</tr></table>";
            ?>
        </section>
    </main>

    <footer>
        <address>
            <p><strong>Alamat:</strong> Jl. Swasembada Barat XIII Tanjung Priok, Jakarta 14320, Indonesia</p>
            <p><strong>Email:</strong> <a href="mailto:info@griyakolektiva.com">info@griyakolektiva.com</a></p>
            <p><strong>Telepon:</strong> <a href="tel:+622123456789">+62 21 2345 6789</a></p>
        </address>
    </footer>
</body>
</html>
