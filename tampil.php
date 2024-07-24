<?php
// Membuka dan membaca file
$file = 'data_peserta.txt';
$fp = fopen($file, 'r');
//memerikksa apakah berhasil membaca file
if ($fp) {
    $no = 1;
    while (($line = fgets($fp)) !== false) {
        // uraikan setiap baris untuk ekstrak data
        // preg_match(pattern, input, matches, flags, offset)
        preg_match('/Nama: (.*), Jenis Kelamin: (.*), Tanggal Lahir: (.*)/', $line, $matches);
        if (count($matches) == 4) {
            $tampilnama = $matches[1];
            $tampiljeniskelamin = $matches[2];
            $tampiltangga = $matches[3];
            $no = $no+1;
        /* Kenapa matches == 4 padahal yang ditampilkan hanya 3?
            $matches[0] = semua string yang sesuai.
            $matches[1] = substring sesuai terhadap (.*?) (untuk "Nama").
            $matches[2] = substring sesuai terhadap (.*?) (untuk "jenis Kelamin").
            $matches[3] = substring sesuai terhadap (.*?) (untuk "Tanggallahir").
        */
        }
    }
    fclose($fp);
} else {
    echo "Gagal membuka dan membaca file";
}
?>
