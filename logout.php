<?php
// Filename: logout.php
// Version: 1.4

// Mulakan sesi
session_start();

// Nyahset semua pembolehubah sesi
$_SESSION = array();

// Musnahkan sesi
session_destroy();

// Halakan pengguna ke halaman log masuk
header("location: login.php");
exit;
?>
