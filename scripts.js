// scripts.js - Frontend interactivity for Griya Kolektiva

document.addEventListener('DOMContentLoaded', () => {
    const searchForm = document.getElementById('searchForm');
    const bookingForm = document.getElementById('bookingForm');

    if (searchForm) {
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const checkin = document.getElementById('checkin').value;
            const checkout = document.getElementById('checkout').value;

            if (new Date(checkin) >= new Date(checkout)) {
                alert('Tanggal check-out harus lebih besar dari tanggal check-in.');
                return;
            }

            // Show available rooms
            const searchResults = document.getElementById('searchResults');
            searchResults.innerHTML = `
                <div class="available-rooms">
                    <h3>Kamar Tersedia</h3>
                    <div class="room-cards">
                        <article class="room-card">
                            <div class="image-placeholder" aria-label="Foto Kamar Ilusi"></div>
                            <h3>Kamar Ilusi</h3>
                            <p>Harga: Rp 110.000 per malam</p>
                            <a href="booking.html?room=1&checkin=${checkin}&checkout=${checkout}" class="book-now-btn">Pesan Sekarang</a>
                        </article>
                        <article class="room-card">
                            <div class="image-placeholder" aria-label="Foto Ruang Kendali"></div>
                            <h3>Ruang Kendali</h3>
                            <p>Harga: Rp 190.000 per malam</p>
                            <a href="booking.html?room=2&checkin=${checkin}&checkout=${checkout}" class="book-now-btn">Pesan Sekarang</a>
                        </article>
                    </div>
                </div>`;
        });
    }

    if (bookingForm) {
        // Pre-fill form based on URL parameters
        const params = new URLSearchParams(window.location.search);
        if (params.has('room')) {
            const roomSelect = document.getElementById('roomSelect');
            roomSelect.value = params.get('room');
        }
        if (params.has('checkin')) {
            const checkinDate = document.getElementById('checkinDate');
            checkinDate.value = params.get('checkin');
        }
        if (params.has('checkout')) {
            const checkoutDate = document.getElementById('checkoutDate');
            checkoutDate.value = params.get('checkout');
        }

        bookingForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(bookingForm);
            const bookingDetails = {
                guestName: formData.get('guestName'),
                guestEmail: formData.get('guestEmail'),
                guestPhone: formData.get('guestPhone'),
                roomSelect: formData.get('roomSelect'),
                checkinDate: formData.get('checkinDate'),
                checkoutDate: formData.get('checkoutDate')
            };

            try {
                // Submit booking to server
                const response = await fetch('process_booking.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(bookingDetails)
                });

                const result = await response.json();

                if (!result.success) {
                    throw new Error(result.message);
                }

                // Calculate total price (also done on server)
                const roomPrices = {
                    '1': 110000, // Kamar Ilusi
                    '2': 190000  // Ruang Kendali
                };
                
                const nights = Math.ceil((new Date(bookingDetails.checkoutDate) - new Date(bookingDetails.checkinDate)) / (1000 * 60 * 60 * 24));
                const totalPrice = roomPrices[bookingDetails.roomSelect] * nights;

                // Show payment details
                const mainContent = document.querySelector('main');
                mainContent.innerHTML = `
                    <section class="payment-details">
                        <h2>Rincian Pembayaran</h2>
                        <div class="booking-summary">
                            <h3>Detail Pemesanan:</h3>
                            <p><strong>Nomor Referensi:</strong> ${result.booking_ref}</p>
                            <p><strong>Nama Tamu:</strong> ${bookingDetails.guestName}</p>
                            <p><strong>Email:</strong> ${bookingDetails.guestEmail}</p>
                            <p><strong>Telepon:</strong> ${bookingDetails.guestPhone}</p>
                            <p><strong>Kamar:</strong> ${bookingDetails.roomSelect === '1' ? 'Kamar Ilusi' : 'Ruang Kendali'}</p>
                            <p><strong>Check-in:</strong> ${bookingDetails.checkinDate}</p>
                            <p><strong>Check-out:</strong> ${bookingDetails.checkoutDate}</p>
                            <p><strong>Jumlah Malam:</strong> ${nights} malam</p>
                            <p><strong>Total Pembayaran:</strong> Rp ${totalPrice.toLocaleString('id-ID')}</p>
                        </div>
                        <div class="payment-instructions">
                            <h3>Instruksi Pembayaran:</h3>
                            <p>Silakan transfer ke rekening berikut:</p>
                            <ul>
                                <li>Bank: Bank Mandiri</li>
                                <li>Nomor Rekening: 123-456-7890</li>
                                <li>Atas Nama: Griya Kolektiva</li>
                            </ul>
                            <p>Setelah melakukan pembayaran, silakan konfirmasi melalui WhatsApp ke nomor yang tertera dengan menyertakan:</p>
                            <ul>
                                <li>Nomor Referensi: ${result.booking_ref}</li>
                                <li>Bukti Transfer</li>
                            </ul>
                            <p>Status pemesanan Anda akan diperbarui setelah konfirmasi pembayaran diterima.</p>
                        </div>
                    </section>`;
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            }
        });
    }
});
