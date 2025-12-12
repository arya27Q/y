document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menuToggle');
    const mainNav = document.getElementById('mainNav');

    if (menuToggle && mainNav) {
        // Tambahkan event listener untuk tombol hamburger
        menuToggle.addEventListener('click', function() {
            // Menambahkan atau menghapus kelas 'active' pada NAV
            // Kelas 'active' ini yang membuat menu terlihat di CSS
            mainNav.classList.toggle('active');
        });
    }
});