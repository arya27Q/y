/** @format */

let bookingList = [];

// Fungsi untuk menghitung jumlah malam menginap
function calculateNights(checkinDateStr, checkoutDateStr) {
  const checkin = new Date(checkinDateStr);
  const checkout = new Date(checkoutDateStr); // Pastikan checkout setelah checkin

  if (checkout <= checkin) {
    return 1; // Minimal 1 malam
  } // Hitung selisih dalam milidetik (milliseconds)

  const diffTime = Math.abs(checkout.getTime() - checkin.getTime()); // Konversi milidetik ke hari dan bulatkan ke atas

  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  return diffDays;
}

function renderBookingList() {
  const ul = document.getElementById("my-booking-list");
  ul.innerHTML = ""; // Kosongkan daftar

  let grandTotal = 0;

  bookingList.forEach((item, index) => {
    const totalNights = calculateNights(item.checkin, item.checkout); // Hitung total harga item: Harga Per Malam * Jumlah Malam
    const itemTotalPrice = item.pricePerNight * totalNights;
    grandTotal += itemTotalPrice;

    const li = document.createElement("li"); // Tampilkan detail durasi dan total harga

    li.innerHTML = `
            ${item.name} 
            (${item.checkin} &rarr; ${item.checkout}) 
            <span style="font-weight: bold;">(${totalNights} malam)</span>
            - Rp${itemTotalPrice.toLocaleString("id-ID")}
        `;
    ul.appendChild(li);
  });

  document.getElementById(
    "total-price"
  ).textContent = `Rp${grandTotal.toLocaleString("id-ID")}`;
}

function addBooking(name, pricePerNight) {
  // price diubah menjadi pricePerNight
  const checkin = document.getElementById("checkin").value;
  const checkout = document.getElementById("checkout").value;

  if (!checkin || !checkout) {
    alert("Silakan pilih tanggal check-in dan check-out dulu!");
    return;
  } // Hitung jumlah malam di JS untuk tampilan

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

    const data = {
      list: finalBookingList,
      total: total,
    };
    console.log("Data dikirim ke PHP:", data);

    try {
      // --- BARIS INI YANG DIUBAH KE ABSOLUT PATH ---
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
