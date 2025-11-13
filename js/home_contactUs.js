// --- Ambil SEMUA elemen di awal ---
const contactBtn = document.getElementById("contactBtn");
const contactOverlay = document.getElementById("contactOverlay");
const contactFormModal = document.getElementById("contactForm"); // Ini adalah modalnya
const closeContact = document.getElementById("closeContact");
const contactForm = document.getElementById("contactFormInner"); // Ini adalah FORM-nya
const contactStatus = document.getElementById('contactStatus'); // Elemen untuk pesan status

// --- Logika Buka/Tutup Modal (dari kode lama Anda, sudah benar) ---
contactBtn.addEventListener("click", () => {
 contactOverlay.style.display = "block";
 contactFormModal.style.display = "block"; // Gunakan variabel modal
});

closeContact.addEventListener("click", () => {
 contactOverlay.style.display = "none";
 contactFormModal.style.display = "none"; // Gunakan variabel modal
});

contactOverlay.addEventListener("click", () => {
contactOverlay.style.display = "none";
contactFormModal.style.display = "none"; // Gunakan variabel modal
});

// --- Logika Submit Form BARU (Menggantikan kode lama Anda) ---
contactForm.addEventListener('submit', async function(event) {

   // 1. HENTIKAN form agar tidak me-reload halaman
 event.preventDefault(); 

 // Ambil tombol submit
 const submitBtn = contactForm.querySelector('.submit-btn');

 // 2. Ambil nilai dari input
 const nama = document.getElementById('name').value;
 const email = document.getElementById('email').value;
 const pesan = document.getElementById('message').value;

 // 3. Ubah tombol menjadi 'Loading'
 submitBtn.disabled = true;
 submitBtn.textContent = "Mengirim...";
 contactStatus.textContent = ""; // Kosongkan pesan status

 // 4. Kirim data ke 'kirim_pesan.php'
 try {
 const response = await fetch('../php/kirim_pesan.php', { // Pastikan path ini benar
 method: 'POST',
 headers: {
'Content-Type': 'application/json'
 },
 body: JSON.stringify({
 nama: nama,
 email: email,
 pesan: pesan
 })
 });

 const result = await response.json();

 if (result.status === 'success') {
 // Jika SUKSES
 contactStatus.style.color = 'green';
 contactStatus.textContent = result.message; // Tampilkan "Pesan terkirim!"

 // Kosongkan form
 contactForm.reset(); 

 } else {
 // Jika GAGAL (misal validasi PHP)
 contactStatus.style.color = 'red';
 contactStatus.textContent = result.message; // Tampilkan "Semua field harus diisi."
 }

 } catch (error) {
 // Jika ada error koneksi
 contactStatus.style.color = 'red';
 contactStatus.textContent = 'Terjadi error koneksi server.';
 }

 // 5. Kembalikan tombol ke semula
     submitBtn.disabled = false;
    submitBtn.textContent = "Kirim";
});