<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil input data dari form
    $nama = $_POST['nama'] ??[];
    $jeniskelamin = $_POST['jeniskelamin'] ?? [];
    $tanggallahir = $_POST['tanggallahir'] ?? [];
    
    //deklarasi variabel untuk validasi
    $pesanerror=[];
    $valid = true;
    //trim input untuk memvalidasi huruf, angka, dan spasi
    foreach ($nama as $key => $value) {
        $trimnama = trim($value);

        //Periksa panjang dan konten huruf, angka, dan spasi
        if (strlen($trimnama) > 60 || !preg_match("/[a-zA-Z\s]+$/", $trimnama)) {
            $pesanerror[] = "Nama peserta ".($trimnama)." tidak valid. Periksa penggunaan simbol spesial dan angka 
            ";
            $valid=false;
        }
        //Memeriksa apakah nama sudah ada atau tidak (case-insensitive) di file
        $filecontent = file_get_contents('data_peserta.txt');
        //pisahkan menjadi baris per baris (lines)
        $lines = explode("\n", $filecontent);
        foreach ($lines as $line) {
            //Pisahkan baris / lines menjadi array, [0] mengakses elemen pertama dalam array
            $namaada = explode(",",$line)[0];
            $namaada = trim(str_replace("Nama: ", "",$namaada));
            //Membandingkan secara case-insensitive apabila ada nama sama tetapi ada huruf kapital
            if (strcasecmp($namaada,$trimnama) === 0) {
                $pesanerror[] = "Nama peserta ".$namaada." sudah ada di dalam database";
                $valid = false;
                break;
            }
        }
    }
    //Jika ada kesalahan validasi, kembali ke form dengan pesan error
    if (!$valid) {
        $error = implode("<br>", $pesanerror);
        header("Location: index.php?error=".urlencode($error));
        exit();
    }
    else {
        //Jika Validasi sukses, lanjutkan ke proses memasukkan data
        $file='data_peserta.txt';
        $data='';

        //Gabung data menjadi string untuk setiap inputan
        foreach ($nama as $key => $value) {
            $data .= "Nama: ".$value.", Jenis Kelamin: ".$jeniskelamin[$key].", Tanggal Lahir: ".$tanggallahir[$key]."\n";
        }
        //Gabung data kedalam file
        file_put_contents($file, $data, FILE_APPEND | LOCK_EX);
        //Kembali ke form dengan pesan sukses
        $sukses = "Data peserta berhasil ditambahkan.";
        header("Location: index.php?sukses=".urlencode($sukses));
        exit();
    }
}
?>
