<?php
            // Koneksi ke database
            $host = 'localhost';
            $user = 'root';
            $password = '';
            $database = 'easybus';

            $koneksi = new mysqli($host, $user, $password, $database);

            // Cek koneksi
            if ($koneksi->connect_error) {
                die("Koneksi gagal: " . $koneksi->connect_error);
            }
            ?>