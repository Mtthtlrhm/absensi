<?php
session_start();

// Tampilkan semua data yang tersimpan di session saat ini
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>