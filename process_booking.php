<?php
session_start();

// Sample room data (in production, this would come from a database)
$rooms = [
    'ruang-kendali' => [
        'id' => 1,
        'name' => 'Ruang Kendali',
        'total_rooms' => 1,
        'available_rooms' => 1,
        'price' => 190000
    ],
    'kamar-ilusi' => [
        'id' => 2,
        'name' => 'Kamar Ilusi',
        'total_rooms' => 4,
        'available_rooms' => 4,
        'price' => 110000
    ]
];

// Function to generate booking reference
function generateBookingRef() {
    return 'BK' . date('Ymd') . rand(1000, 9999);
}

// Function to check room availability
function isRoomAvailable($room_type, $checkin_date, $checkout_date) {
    global $rooms;
    if (!isset($rooms[$room_type])) {
        return false;
    }
    return $rooms[$room_type]['available_rooms'] > 0;
}

// Function to update room availability
function updateRoomAvailability($room_type, $increment = false) {
    global $rooms;
    if (isset($rooms[$room_type])) {
        if ($increment) {
            $rooms[$room_type]['available_rooms']++;
        } else {
            $rooms[$room_type]['available_rooms']--;
        }
        return true;
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $guest_name = $_POST['guest_name'] ?? '';
    $guest_email = $_POST['guest_email'] ?? '';
    $guest_phone = $_POST['guest_phone'] ?? '';
    $room_type = $_POST['room_type'] ?? '';
    $checkin_date = $_POST['checkin_date'] ?? '';
    $checkout_date = $_POST['checkout_date'] ?? '';
    $total_guests = (int)($_POST['total_guests'] ?? 1);

    // Validate input
    if (!$guest_name || !$guest_email || !$guest_phone || !$room_type || !$checkin_date || !$checkout_date) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Semua field harus diisi'
        ]);
        exit;
    }

    // Check room availability
    if (!isRoomAvailable($room_type, $checkin_date, $checkout_date)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Kamar tidak tersedia untuk tanggal yang dipilih'
        ]);
        exit;
    }

    // Calculate total price
    $checkin = new DateTime($checkin_date);
    $checkout = new DateTime($checkout_date);
    $nights = $checkout->diff($checkin)->days;
    $price_per_night = $rooms[$room_type]['price'];
    $total_price = $nights * $price_per_night;

    // Generate booking reference
    $booking_ref = generateBookingRef();

    // Create booking details
    $booking_details = [
        'booking_ref' => $booking_ref,
        'guest_name' => $guest_name,
        'guest_email' => $guest_email,
        'guest_phone' => $guest_phone,
        'room_type' => $room_type,
        'room_name' => $rooms[$room_type]['name'],
        'checkin_date' => $checkin_date,
        'checkout_date' => $checkout_date,
        'total_guests' => $total_guests,
        'nights' => $nights,
        'price_per_night' => $price_per_night,
        'total_price' => $total_price,
        'status' => 'pending'
    ];

    // Store booking details in session
    $_SESSION['booking_details'] = $booking_details;

    // Update room availability
    updateRoomAvailability($room_type);

    // Redirect to payment page
    header('Location: payment.php');
    exit;
}

// If not POST request, redirect to booking page
header('Location: booking.html');
exit;
?>
