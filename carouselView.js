document.addEventListener('DOMContentLoaded', function() {
  // Inisialisasi semua carousel
  document.querySelectorAll('.image-carousel').forEach(function(carousel) {
    const sliderContainer = carousel.querySelector('.slider-container');
    const slides = carousel.querySelectorAll('.room-image');
    const prevArrow = carousel.querySelector('.prev-arrow');
    const nextArrow = carousel.querySelector('.next-arrow');
    const indicators = carousel.querySelectorAll('.indicator');
    
    let currentSlide = 0;
    const slideCount = slides.length;
    
    // Fungsi untuk menampilkan slide tertentu
    function showSlide(index) {
      if (index < 0) {
        currentSlide = slideCount - 1;
      } else if (index >= slideCount) {
        currentSlide = 0;
      } else {
        currentSlide = index;
      }
      
      // Geser slider ke slide yang aktif
      sliderContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
      
      // Update indikator aktif
      indicators.forEach((indicator, i) => {
        indicator.classList.toggle('active', i === currentSlide);
      });
    }
    
    // Event listener untuk tombol navigasi
    prevArrow.addEventListener('click', () => {
      showSlide(currentSlide - 1);
    });
    
    nextArrow.addEventListener('click', () => {
      showSlide(currentSlide + 1);
    });
    
    // Event listener untuk indikator
    indicators.forEach((indicator, index) => {
      indicator.addEventListener('click', () => {
        showSlide(index);
      });
    });
    
    // Auto slide setiap 5 detik (opsional)
    let autoSlide = setInterval(() => {
      showSlide(currentSlide + 1);
    }, 5000);
    
    // Hentikan auto slide saat hover
    carousel.addEventListener('mouseenter', () => {
      clearInterval(autoSlide);
    });
    
    // Lanjutkan auto slide setelah hover
    carousel.addEventListener('mouseleave', () => {
      autoSlide = setInterval(() => {
        showSlide(currentSlide + 1);
      }, 5000);
    });
    
    // Inisialisasi slide pertama
    showSlide(0);
  });
});
