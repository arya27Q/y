<?php
// koneksi mysqli procedural untuk Laragon (localhost, root, tanpa password)
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'web_hotel';

$koneksi = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (!$koneksi) {
    die('Connection failed: ' . mysqli_connect_error());
}
