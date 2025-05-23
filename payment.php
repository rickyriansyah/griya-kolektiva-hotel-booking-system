<?php
session_start();

if (!isset($_SESSION['booking_details'])) {
    header('Location: booking.html');
    exit;
}

$booking = $_SESSION['booking_details'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Griya Kolektiva</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Griya Kolektiva</h1>
        <nav>
            <ul>
                <li><a href="index.html">Beranda</a></li>
                <li><a href="rooms.html">Kamar</a></li>
                <li><a href="booking.html">Pemesanan</a></li>
                <li><a href="admin_login.php">Admin</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="payment-section">
            <h2>Detail Pembayaran</h2>
            
            <div class="booking-summary">
                <h3>Ringkasan Pemesanan</h3>
                <div class="summary-details">
                    <div class="summary-item">
                        <span>Kode Booking:</span>
                        <span><?php echo $booking['booking_ref']; ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Nama Tamu:</span>
                        <span><?php echo htmlspecialchars($booking['guest_name']); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Kamar:</span>
                        <span><?php echo htmlspecialchars($booking['room_name']); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Check-in:</span>
                        <span><?php echo $booking['checkin_date']; ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Check-out:</span>
                        <span><?php echo $booking['checkout_date']; ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Jumlah Malam:</span>
                        <span><?php echo $booking['nights']; ?> malam</span>
                    </div>
                    <div class="summary-item total">
                        <span>Total Pembayaran:</span>
                        <span>Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></span>
                    </div>
                </div>
            </div>

            <div class="payment-method">
                <h3>Metode Pembayaran</h3>
                <div class="bank-transfer">
                    <h4>Transfer Bank</h4>
                    <div class="bank-details">
                        <div class="bank-account">
                            <p class="bank-name">Bank BCA</p>
                            <p class="account-number">0073252251</p>
                            <p class="account-name">a.n. Sadiah Samin</p>
                        </div>
                    </div>
                    <div class="payment-instructions">
                        <h4>Instruksi Pembayaran:</h4>
                        <ol>
                            <li>Transfer sesuai dengan total pembayaran ke rekening di atas</li>
                            <li>Simpan bukti transfer</li>
                            <li>Jika ada kendala, kirim bukti transfer melalui WhatsApp ke nomor 085819534878</li>
                            <li>Sertakan kode booking <?php echo $booking['booking_ref']; ?> pada pesan WhatsApp</li>
                            <li>Pembayaran akan dikonfirmasi dalam 1x24 jam</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="payment-actions">
                <a href="booking.html" class="btn-secondary">Kembali</a>
                <button onclick="window.print()" class="btn-primary">Cetak Detail Pembayaran</button>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Griya Kolektiva</p>
    </footer>
</body>
</html>
