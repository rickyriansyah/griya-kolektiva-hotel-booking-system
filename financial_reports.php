<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Sample room data
$rooms = [
    [
        'id' => 1,
        'name' => 'Ruang Kendali',
        'total_rooms' => 1,
        'available_rooms' => 1,
        'price' => 190000,
        'revenue' => 0,
        'bookings' => 0
    ],
    [
        'id' => 2,
        'name' => 'Kamar Ilusi',
        'total_rooms' => 4,
        'available_rooms' => 4,
        'price' => 110000,
        'revenue' => 0,
        'bookings' => 0
    ]
];

// Sample financial statistics
$financial_stats = [
    'total_revenue' => 0,
    'pending_payments' => 0,
    'confirmed_payments' => 0,
    'monthly_revenue' => [
        'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0,
        'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0,
        'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0
    ]
];

// Get selected date range (default to current month)
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - Griya Kolektiva</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Laporan Keuangan</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="manage_rooms.php">Kelola Kamar</a></li>
                <li><a href="financial_reports.php" aria-current="page">Laporan Keuangan</a></li>
                <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="financial-overview">
            <h2>Ringkasan Keuangan</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Pendapatan</h3>
                    <p>Rp <?php echo number_format($financial_stats['total_revenue'], 0, ',', '.'); ?></p>
                </div>
                <div class="stat-card">
                    <h3>Pembayaran Tertunda</h3>
                    <p>Rp <?php echo number_format($financial_stats['pending_payments'], 0, ',', '.'); ?></p>
                </div>
                <div class="stat-card">
                    <h3>Pembayaran Terkonfirmasi</h3>
                    <p>Rp <?php echo number_format($financial_stats['confirmed_payments'], 0, ',', '.'); ?></p>
                </div>
            </div>
        </section>

        <section class="room-revenue">
            <h2>Pendapatan per Kamar</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Kamar</th>
                            <th>Total Kamar</th>
                            <th>Harga per Malam</th>
                            <th>Total Pemesanan</th>
                            <th>Total Pendapatan</th>
                            <th>Okupansi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rooms as $room): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($room['name']); ?></td>
                            <td><?php echo $room['total_rooms']; ?></td>
                            <td>Rp <?php echo number_format($room['price'], 0, ',', '.'); ?></td>
                            <td><?php echo $room['bookings']; ?></td>
                            <td>Rp <?php echo number_format($room['revenue'], 0, ',', '.'); ?></td>
                            <td>
                                <?php 
                                $occupancy = $room['total_rooms'] > 0 ? 
                                    (($room['total_rooms'] - $room['available_rooms']) / $room['total_rooms']) * 100 : 0;
                                echo number_format($occupancy, 1) . '%';
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="transaction-history">
            <h2>Riwayat Transaksi</h2>
            <div class="filter-form">
                <form method="GET" class="date-filter">
                    <div class="form-group">
                        <label for="start_date">Tanggal Mulai:</label>
                        <input type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
                    </div>
                    <div class="form-group">
                        <label for="end_date">Tanggal Selesai:</label>
                        <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
                    </div>
                    <button type="submit" class="btn-filter">Filter</button>
                </form>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kode Booking</th>
                            <th>Kamar</th>
                            <th>Tamu</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" class="no-data">Belum ada transaksi</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="monthly-revenue">
            <h2>Pendapatan Bulanan</h2>
            <div class="chart-container">
                <table class="revenue-chart">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Pendapatan</th>
                            <th>Grafik</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $max_revenue = max($financial_stats['monthly_revenue']);
                        foreach ($financial_stats['monthly_revenue'] as $month => $revenue): 
                        $percentage = $max_revenue > 0 ? ($revenue / $max_revenue) * 100 : 0;
                        ?>
                        <tr>
                            <td><?php echo $month; ?></td>
                            <td>Rp <?php echo number_format($revenue, 0, ',', '.'); ?></td>
                            <td>
                                <div class="bar-chart">
                                    <div class="bar" style="width: <?php echo $percentage; ?>%"></div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
