<?php
include '../php/session_check.php';
include '../php/config.php';

// Pastikan ada ID yang dikirim
if (isset($_GET['id'])) {
    $payment_id = intval($_GET['id']); // Pastikan integer untuk keamanan

    // Query data pembayaran + data tamu
    $sql = "SELECT p.*, t.nama_lengkap, t.email, t.no_telepon 
            FROM pembayaran p 
            JOIN tamu t ON p.id_tamu = t.id_tamu 
            WHERE p.payment_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        die("Data pembayaran tidak ditemukan.");
    }
} else {
    die("ID Pembayaran tidak valid.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo $data['payment_id']; ?></title>
    
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
            background: #f5f5f5;
        }
        .invoice-box {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            background: #fff;
        }
        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }
        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }
        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }
        .title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }
        .header-info {
            margin-bottom: 20px;
        }
        .heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        .item td {
            border-bottom: 1px solid #eee;
        }
        .item.last td {
            border-bottom: none;
        }
        .total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
            font-size: 18px;
        }
        .status-paid {
            color: green;
            font-weight: bold;
            border: 2px solid green;
            padding: 5px 10px;
            display: inline-block;
            transform: rotate(-10deg);
            opacity: 0.8;
        }
        .status-pending {
            color: orange;
            font-weight: bold;
            border: 2px solid orange;
            padding: 5px 10px;
            display: inline-block;
        }

        /* CSS Khusus Print */
        @media print {
            body { background: #fff; }
            .invoice-box { box-shadow: none; border: 0; margin: 0; padding: 0; }
            .no-print { display: none; }
        }

        /* Tombol Print */
        .btn-print {
            display: block;
            width: 100%;
            max-width: 200px;
            margin: 20px auto;
            padding: 10px;
            background: #002877;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-print:hover { background: #001a53; }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">🖨️ Cetak Invoice</button>
    </div>

    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <span style="color: #002877;">Luxury Hotel</span>
                            </td>
                            
                            <td>
                                Invoice #: <strong><?php echo $data['payment_id']; ?></strong><br>
                                Tanggal: <?php echo date("d F Y", strtotime($data['tanggal_pembayaran'] ?? 'now')); ?><br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                <strong>Luxury Hotel Management</strong><br>
                                Jl. Raya Hotel Mewah No. 1<br>
                                Surabaya, Indonesia
                            </td>
                            
                            <td>
                                <strong>Ditagihkan Kepada:</strong><br>
                                <?php echo htmlspecialchars($data['nama_lengkap'] ?? ''); ?><br>
                                <?php echo htmlspecialchars($data['email'] ?? ''); ?><br>
                                <?php echo htmlspecialchars($data['no_telepon'] ?? ''); ?> 
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="heading">
                <td>Metode Pembayaran</td>
                <td>Status</td>
            </tr>
            
            <tr class="details">
                <td><?php echo htmlspecialchars($data['metode_pembayaran'] ?? '-'); ?></td>
                <td>
                    <?php 
                        $status = $data['status_pembayaran'] ?? '';
                        if($status == 'Paid' || $status == 'Lunas') {
                            echo '<span class="status-paid">LUNAS</span>';
                        } else {
                            echo '<span class="status-pending">'.strtoupper($status).'</span>';
                        }
                    ?>
                </td>
            </tr>
            
            <tr class="heading">
                <td>Deskripsi Item</td>
                <td>Harga</td>
            </tr>
            
            <tr class="item">
                <td>
                    Pembayaran Reservasi <?php echo ucfirst($data['jenis_reservasi'] ?? ''); ?> <br>
                    <small style="color: #777;">Ref ID: <?php echo $data['id_reservasi_ref'] ?? '-'; ?></small>
                </td>
                <td>
                    Rp <?php echo number_format($data['total_amount'] ?? 0, 0, ',', '.'); ?>
                </td>
            </tr>
            
            <tr class="total">
                <td></td>
                <td>
                   Total: Rp <?php echo number_format($data['total_amount'] ?? 0, 0, ',', '.'); ?>
                </td>
            </tr>
        </table>
        
        <div style="margin-top: 40px; text-align: center; font-size: 12px; color: #777;">
            <p>Terima kasih atas kunjungan Anda di Luxury Hotel.</p>
            <p>Bukti pembayaran ini sah dan diproses secara komputerisasi.</p>
        </div>
    </div>

    <script>
        // Opsional: Otomatis buka dialog print saat halaman dimuat
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>