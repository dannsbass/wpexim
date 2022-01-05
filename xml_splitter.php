<?php
/**
 * Nama         :   Pembelah File XML Wordpress
 * Deskripsi    :   Script sederhana untuk membelah file XML yang didapatkan melalui proses export konten di WOrdpress agar menjadi kecil sehingga bisa diimport ke Wordpress lagi saat diperlukan. 
 * Pengembang   :   Dannsbass
 * Email        :   dannsbass@gmail.com
 * Github       :   https://github.com/dannsbass
 * 
 */
//nama file XML yang akan dipecah
$myXML = 'ًwordpress-posts.xml';
//ubah menjadi array
$file = file(__DIR__ . "/$myXML");
//hasil potongan pertama
$awalXML = 'awal.xml';
//hasil potongan terakhir
$akhirXML = 'akhir.xml';
//batas header (sebelum <item> pertama)
$batasHeader = 42;

//masukkan dulu header ke file 
for ($i=0; $i<$batasHeader; $i++){
    file_put_contents($akhirXML, $file[$i], FILE_APPEND | LOCK_EX);
}

$items = [];
$noItem = 0;

foreach ($file as $baris => $isi) {
    if (strpos($isi, '<item>') != false) $noItem++;
    if ($noItem >= 7097 /** dari nilai tengah total <item> */) {
        file_put_contents($akhirXML, $isi, FILE_APPEND | LOCK_EX);
    } else {
        file_put_contents($awalXML, $isi, FILE_APPEND | LOCK_EX);
    }
}

$footer = array_slice($file,-3); // untuk mengambil </channel> dan </rss>
foreach ($footer as $key => $value) {
    file_put_contents($awalXML, $value, FILE_APPEND | LOCK_EX);
}