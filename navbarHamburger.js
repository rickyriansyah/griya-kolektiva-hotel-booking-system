// Toggle the navigation menu on mobile
const menuToggle = document.getElementById('menuToggle');
const nav = document.querySelector('nav');

menuToggle.addEventListener('click', () => {
  // Toggle the 'open' class on the <nav> element
  nav.classList.toggle('open');
  
  // Toggle the 'active' class on the hamburger icon to animate it
  menuToggle.classList.toggle('active');
});
