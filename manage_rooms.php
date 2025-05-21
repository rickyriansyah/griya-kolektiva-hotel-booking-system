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
        'description' => 'Ruang kendali dengan fasilitas lengkap',
        'status' => 'available'
    ],
    [
        'id' => 2,
        'name' => 'Kamar Ilusi',
        'total_rooms' => 4,
        'available_rooms' => 4,
        'price' => 110000,
        'description' => 'Kamar dengan desain ilusi yang unik',
        'status' => 'available'
    ]
];

// Handle room updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $room_id = (int)($_POST['room_id'] ?? 0);
    
    if ($action === 'update_room') {
        foreach ($rooms as &$room) {
            if ($room['id'] === $room_id) {
                $room['name'] = $_POST['name'] ?? $room['name'];
                $room['price'] = (int)($_POST['price'] ?? $room['price']);
                $room['total_rooms'] = (int)($_POST['total_rooms'] ?? $room['total_rooms']);
                $room['available_rooms'] = (int)($_POST['available_rooms'] ?? $room['available_rooms']);
                $room['description'] = $_POST['description'] ?? $room['description'];
                break;
            }
        }
        // In production, update database here
    }
}

// Get room data for editing
function getRoomData($room_id) {
    global $rooms;
    foreach ($rooms as $room) {
        if ($room['id'] === $room_id) {
            return json_encode($room);
        }
    }
    return '{}';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kamar - Griya Kolektiva</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Kelola Kamar</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="manage_rooms.php" aria-current="page">Kelola Kamar</a></li>
                <li><a href="financial_reports.php">Laporan Keuangan</a></li>
                <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="room-management">
            <div class="room-stats">
                <div class="stat-card">
                    <h3>Total Kamar</h3>
                    <p><?php echo array_sum(array_column($rooms, 'total_rooms')); ?></p>
                </div>
                <div class="stat-card">
                    <h3>Kamar Tersedia</h3>
                    <p><?php echo array_sum(array_column($rooms, 'available_rooms')); ?></p>
                </div>
            </div>

            <div class="room-list">
                <h2>Daftar Kamar</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Kamar</th>
                            <th>Total Kamar</th>
                            <th>Kamar Tersedia</th>
                            <th>Harga per Malam</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rooms as $room): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($room['name']); ?></td>
                            <td><?php echo $room['total_rooms']; ?></td>
                            <td><?php echo $room['available_rooms']; ?></td>
                            <td>Rp <?php echo number_format($room['price'], 0, ',', '.'); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $room['status']; ?>">
                                    <?php echo ucfirst($room['status']); ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn-edit" onclick="editRoom(<?php echo $room['id']; ?>)">Edit</button>
                                <button class="btn-update" onclick="updateAvailability(<?php echo $room['id']; ?>)">Update Ketersediaan</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Modal for editing room -->
        <div id="editRoomModal" class="modal">
            <div class="modal-content">
                <h2>Edit Kamar</h2>
                <form id="editRoomForm" method="POST">
                    <input type="hidden" name="action" value="update_room">
                    <input type="hidden" name="room_id" id="editRoomId">
                    
                    <div class="form-group">
                        <label for="editRoomName">Nama Kamar:</label>
                        <input type="text" id="editRoomName" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="editRoomPrice">Harga per Malam:</label>
                        <input type="number" id="editRoomPrice" name="price" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="editTotalRooms">Total Kamar:</label>
                        <input type="number" id="editTotalRooms" name="total_rooms" required min="1">
                    </div>
                    
                    <div class="form-group">
                        <label for="editAvailableRooms">Kamar Tersedia:</label>
                        <input type="number" id="editAvailableRooms" name="available_rooms" required min="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="editRoomDescription">Deskripsi:</label>
                        <textarea id="editRoomDescription" name="description" required></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-save">Simpan</button>
                        <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal for updating availability -->
        <div id="availabilityModal" class="modal">
            <div class="modal-content">
                <h2>Update Ketersediaan Kamar</h2>
                <form id="availabilityForm" method="POST">
                    <input type="hidden" name="action" value="update_availability">
                    <input type="hidden" name="room_id" id="availabilityRoomId">
                    
                    <div class="form-group">
                        <label for="availableRooms">Jumlah Kamar Tersedia:</label>
                        <input type="number" id="availableRooms" name="available_rooms" required min="0">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-save">Simpan</button>
                        <button type="button" class="btn-cancel" onclick="closeAvailabilityModal()">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
    const rooms = <?php echo json_encode($rooms); ?>;
    
    function editRoom(roomId) {
        const room = rooms.find(r => r.id === roomId);
        if (room) {
            document.getElementById('editRoomId').value = room.id;
            document.getElementById('editRoomName').value = room.name;
            document.getElementById('editRoomPrice').value = room.price;
            document.getElementById('editTotalRooms').value = room.total_rooms;
            document.getElementById('editAvailableRooms').value = room.available_rooms;
            document.getElementById('editRoomDescription').value = room.description;
            document.getElementById('editRoomModal').style.display = 'block';
        }
    }

    function updateAvailability(roomId) {
        const room = rooms.find(r => r.id === roomId);
        if (room) {
            document.getElementById('availabilityRoomId').value = room.id;
            document.getElementById('availableRooms').value = room.available_rooms;
            document.getElementById('availabilityModal').style.display = 'block';
        }
    }

    function closeModal() {
        document.getElementById('editRoomModal').style.display = 'none';
    }

    function closeAvailabilityModal() {
        document.getElementById('availabilityModal').style.display = 'none';
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        if (event.target == document.getElementById('editRoomModal')) {
            closeModal();
        }
        if (event.target == document.getElementById('availabilityModal')) {
            closeAvailabilityModal();
        }
    }

    // Validate available rooms cannot exceed total rooms
    document.getElementById('editRoomForm').addEventListener('submit', function(e) {
        const totalRooms = parseInt(document.getElementById('editTotalRooms').value);
        const availableRooms = parseInt(document.getElementById('editAvailableRooms').value);
        
        if (availableRooms > totalRooms) {
            e.preventDefault();
            alert('Jumlah kamar tersedia tidak boleh melebihi total kamar');
        }
    });

    document.getElementById('availabilityForm').addEventListener('submit', function(e) {
        const roomId = parseInt(document.getElementById('availabilityRoomId').value);
        const room = rooms.find(r => r.id === roomId);
        const availableRooms = parseInt(document.getElementById('availableRooms').value);
        
        if (availableRooms > room.total_rooms) {
            e.preventDefault();
            alert('Jumlah kamar tersedia tidak boleh melebihi total kamar');
        }
    });
    </script>
</body>
</html>
