/** @format */

let bookingList = [];

// Fungsi untuk menghitung jumlah malam menginap
function calculateNights(checkinDateStr, checkoutDateStr) {
  const checkin = new Date(checkinDateStr);
  const checkout = new Date(checkoutDateStr); // Pastikan checkout setelah checkin

  if (checkout <= checkin) {
    return 1; // Minimal 1 malam
  } // Hitung selih dalam milidetik (milliseconds)

  const diffTime = Math.abs(checkout.getTime() - checkin.getTime()); // Konversi milidetik ke hari dan bulatkan ke atas

  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  return diffDays;
}

function renderBookingList() {
    const ul = document.getElementById("my-booking-list");
    ul.innerHTML = ""; // Kosongkan daftar

    let grandTotal = 0;

    bookingList.forEach((item, index) => {
        const totalNights = calculateNights(item.checkin, item.checkout); 
        const itemTotalPrice = item.pricePerNight * totalNights;
        grandTotal += itemTotalPrice;

        const li = document.createElement("li"); 
        li.innerHTML = `
        ${item.name} 
        (${item.checkin} &rarr; ${item.checkout}) 
        <span style="font-weight: bold;">(${totalNights} malam)</span>
        - Rp${itemTotalPrice.toLocaleString("id-ID")}
        `;
        ul.appendChild(li);
    });

    // --- PERUBAHAN PENTING DI SINI ---
    const formattedTotal = `Rp${grandTotal.toLocaleString("id-ID")}`;

    // 1. Update Total Asli (seperti biasa)
    document.getElementById("total-price").textContent = formattedTotal;

    // 2. UPDATE JUGA Grand Total (agar sama dengan total asli)
    document.getElementById("grand-total-price").textContent = formattedTotal; 
    
    // 3. BERSIHKAN Info Promo (karena keranjang berubah)
    document.getElementById("info_promo").textContent = ""; 
    // --- AKHIR PERUBAHAN ---
}

function addBooking(name, pricePerNight) {
  // price diubah menjadi pricePerNight
  const checkin = document.getElementById("checkin").value;
  const checkout = document.getElementById("checkout").value;

  if (!checkin || !checkout) {
    alert("Silakan pilih tanggal check-in dan check-out dulu!");
    return;
  }

  // --- EDIT DITAMBAHKAN DI SINI ---
  // Validasi agar tanggal check-out tidak sebelum check-in
  const checkinDate = new Date(checkin);
  const checkoutDate = new Date(checkout);

  if (checkoutDate <= checkinDate) {
    alert("Tanggal check-out harus setelah tanggal check-in!");
    return;
  }
  // --- AKHIR EDIT ---

  // Hitung jumlah malam di JS untuk tampilan
  const totalNights = calculateNights(checkin, checkout); // Tambah data ke array

  bookingList.push({
    name: name,
    pricePerNight: pricePerNight, // Kirim harga per malam (PHP yang akan hitung final)
    checkin: checkin,
    checkout: checkout, // total_biaya: pricePerNight * totalNights // Ini hanya untuk tampilan di JS
  });

  renderBookingList();
  console.log("Booking list sekarang:", bookingList);
}

document
  .getElementById("bookNow")
  .addEventListener("click", async function (e) {
    e.preventDefault();

    if (bookingList.length === 0) {
      alert("Belum ada booking kamar!");
      return;
    } // Siapkan data untuk dikirim ke PHP

    let total = 0;
    const finalBookingList = bookingList.map((item) => {
      const nights = calculateNights(item.checkin, item.checkout);
      const itemTotal = item.pricePerNight * nights;
      total += itemTotal; // Data yang dikirim ke PHP

      return {
        name: item.name,
        price: item.pricePerNight, // Kirim harga per malam (PHP akan mengalikan dengan durasi)
        checkin: item.checkin,
        checkout: item.checkout,
        jumlah_tamu: 1, // Default
      };
    });

    // ...
    // AMBIL NILAI DARI KOTAK KODE PROMO
    const promoCodeValue = document.getElementById("kode_promo_input").value;

    const data = {
      list: finalBookingList,
      total: total,
      promo_code: promoCodeValue  // <-- TAMBAHKAN BARIS INI
      };

   console.log("Data dikirim ke PHP:", data); // Cek konsol, pastikan promo_code ada

    try {
      const response = await fetch("/web-hotel/php/reservasi_hotel.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });

      const result = await response.json();
      console.log("Response dari PHP:", result);

      if (result.status === "success") {
        alert("Reservasi berhasil! Silakan lanjut ke pembayaran.");
        window.location.href = "payment_reservation_room.php";
      } else {
        alert("Reservasi gagal: " + result.message);
      }
    } catch (error) {
      console.error("Error saat fetch:", error);
      alert("Terjadi kesalahan koneksi ke server.");
    }
  });

  // Tambahkan kode ini di file JavaScript Anda (di luar fungsi lain)

document.getElementById('tombol_apply_promo').addEventListener('click', async function() {
    
    const promoCodeValue = document.getElementById('kode_promo_input').value;
    const infoPromoDiv = document.getElementById('info_promo');
    const grandTotalSpan = document.getElementById('grand-total-price'); // Target span Grand Total

    // 1. Hitung ulang total asli dari 'bookingList'
    let originalTotal = 0;
    bookingList.forEach(item => {
        const nights = calculateNights(item.checkin, item.checkout);
        originalTotal += item.pricePerNight * nights;
    });

    // Jika keranjang kosong, beri peringatan
    if (originalTotal === 0) {
        infoPromoDiv.textContent = 'Silakan pilih kamar dulu.';
        infoPromoDiv.style.color = 'red';
        return;
    }

    // 2. Kirim data ke file PHP baru
    try {
        const response = await fetch('/web-hotel/php/cek_promo.php', { // Pastikan path ini benar
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                promo_code: promoCodeValue,
                original_total: originalTotal
            })
        });

        const result = await response.json();

        if (result.status === 'success') {
            // 3. Update tampilan jika SUKSES
            infoPromoDiv.textContent = result.message;
            infoPromoDiv.style.color = 'green';
            // Update Grand Total dengan harga baru
            grandTotalSpan.textContent = `Rp${result.newTotal.toLocaleString('id-ID')}`;
        
        } else {
            // 4. Update tampilan jika GAGAL
            infoPromoDiv.textContent = result.message;
            infoPromoDiv.style.color = 'red';
            // Kembalikan Grand Total ke harga asli
            grandTotalSpan.textContent = `Rp${originalTotal.toLocaleString('id-ID')}`;
        }

    } catch (error) {
        console.error('Error saat cek promo:', error);
        infoPromoDiv.textContent = 'Gagal terhubung ke server.';
        infoPromoDiv.style.color = 'red';
    }
});