<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: admin_dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Hard-coded admin credentials for now
    $admin_username = 'admin';
    $admin_password = 'admin123';

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_id'] = 1;
        $_SESSION['admin_name'] = 'Administrator';
        header('Location: admin_dashboard.php');
        exit;
    } else {
        $error = 'Username atau password salah';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Griya Kolektiva</title>
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
                <li><a href="admin_login.php" aria-current="page">Admin</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="admin-login">
            <h2>Login Admin</h2>
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="submit-btn">Login</button>
            </form>
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
