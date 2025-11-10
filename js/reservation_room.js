let bookingList = [];

function addBooking(name, price) {
  const checkin = document.getElementById("checkin").value;
  const checkout = document.getElementById("checkout").value;

  if (!checkin || !checkout) {
    alert("Silakan pilih tanggal check-in dan check-out dulu!");
    return;
  }

  // Tambah data ke array
  bookingList.push({
    name: name,
    price: price,
    checkin: checkin,
    checkout: checkout
  });

  // Tampilkan ke daftar booking
  const ul = document.getElementById("my-booking-list");
  const li = document.createElement("li");
  li.textContent = `${name} (${checkin} - ${checkout}) - Rp${price.toLocaleString()}`;
  ul.appendChild(li);

  const total = bookingList.reduce((acc, item) => acc + item.price, 0);
  document.getElementById("total-price").textContent = total.toLocaleString();

  console.log("Booking list sekarang:", bookingList);
}

document.getElementById("bookNow").addEventListener("click", async function (e) {
  e.preventDefault();

  if (bookingList.length === 0) {
    alert("Belum ada booking kamar!");
    return;
  }

  let total = bookingList.reduce((acc, item) => acc + item.price, 0);

  const data = { list: bookingList, total: total };
  console.log("Data dikirim ke PHP:", data);

  try {
    const response = await fetch("../php/reservasi_hotel.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data)
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
