<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Sample room data (in production, this would come from a database)
$rooms = [
    [
        'id' => 1,
        'name' => 'Ruang Kendali',
        'total_rooms' => 1,
        'available_rooms' => 1,
        'price' => 190000,
        'booked' => 0
    ],
    [
        'id' => 2,
        'name' => 'Kamar Ilusi',
        'total_rooms' => 4,
        'available_rooms' => 4,
        'price' => 110000,
        'booked' => 0
    ]
];

// Sample booking statistics
$stats = [
    'total_bookings' => 0,
    'pending_bookings' => 0,
    'confirmed_bookings' => 0,
    'cancelled_bookings' => 0,
    'total_revenue' => 0
];

// Sample bookings data
$bookings = [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Griya Kolektiva</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Dashboard Admin</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php" aria-current="page">Dashboard</a></li>
                <li><a href="manage_rooms.php">Kelola Kamar</a></li>
                <li><a href="financial_reports.php">Laporan Keuangan</a></li>
                <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="dashboard-overview">
            <h2>Statistik Pemesanan</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Pemesanan</h3>
                    <p><?php echo $stats['total_bookings']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Menunggu Konfirmasi</h3>
                    <p><?php echo $stats['pending_bookings']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Terkonfirmasi</h3>
                    <p><?php echo $stats['confirmed_bookings']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Dibatalkan</h3>
                    <p><?php echo $stats['cancelled_bookings']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Pendapatan</h3>
                    <p>Rp <?php echo number_format($stats['total_revenue'], 0, ',', '.'); ?></p>
                </div>
            </div>
        </section>

        <section class="room-availability">
            <h2>Ketersediaan Kamar</h2>
            <div class="room-grid">
                <?php foreach ($rooms as $room): ?>
                <div class="room-card">
                    <h3><?php echo htmlspecialchars($room['name']); ?></h3>
                    <div class="room-details">
                        <p>Total Kamar: <?php echo $room['total_rooms']; ?></p>
                        <p>Tersedia: <?php echo $room['available_rooms']; ?></p>
                        <p>Terpesan: <?php echo $room['booked']; ?></p>
                        <p>Harga: Rp <?php echo number_format($room['price'], 0, ',', '.'); ?>/malam</p>
                    </div>
                    <div class="availability-indicator <?php echo $room['available_rooms'] > 0 ? 'available' : 'full'; ?>">
                        <?php echo $room['available_rooms'] > 0 ? 'Tersedia' : 'Penuh'; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="recent-bookings">
            <h2>Pemesanan Terbaru</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Kode Booking</th>
                            <th>Tamu</th>
                            <th>Kamar</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bookings)): ?>
                        <tr>
                            <td colspan="8" class="no-data">Belum ada pemesanan</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['booking_ref']); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($booking['guest_name']); ?><br>
                                    <small><?php echo htmlspecialchars($booking['guest_phone']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($booking['room_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['checkin_date']); ?></td>
                                <td><?php echo htmlspecialchars($booking['checkout_date']); ?></td>
                                <td>Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $booking['status']; ?>">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($booking['status'] === 'pending'): ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                        <button type="submit" name="action" value="confirm" class="btn-confirm">Konfirmasi</button>
                                        <button type="submit" name="action" value="cancel" class="btn-cancel">Batalkan</button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
