<?php
/*
script ini berfungsi untuk menyuntikkan data yang sudah disiapkan ke dalam tabel wordpress,
tujuannya supaya data bisa diekspor dari wordpress lokal dan diimpor kembali oleh wordpress remote
*/

$testdata_koneksi = mysqli_connect('localhost', 'root', '', 'testdata'); //koneksi ke database lokal
// $wordpress_koneksi = mysqli_connect('localhost', 'root', '', 'wordpress'); //koneksi ke database lokal


for ($nomor_kitab = 3; $nomor_kitab <= 9; $nomor_kitab++) {
    
    $time = strtotime("2021-01-0$nomor_kitab 00:00:00");
    
    $author = $nomor_kitab + 1;
    
    $jumlah_hadis = (mysqli_query(mysqli_connect('localhost', 'root', '', 'testdata'), "SELECT id FROM database_hadis WHERE nama_kitab = $nomor_kitab"))->num_rows;

    for ($bid = 1; $bid <= $jumlah_hadis; $bid++) {
        
        $date = date("Y-m-d H:i:s", $time);
        
        $query = mysqli_query($testdata_koneksi, "SELECT * FROM database_hadis WHERE nama_kitab = $nomor_kitab AND hno = $bid");
        
        $data = mysqli_fetch_assoc($query);
        // var_dump($data); die;

        $nass = $data['nass'];
        $terjemah = $data['terjemah'];
        $hno = $data['hno'];
        $nama_kitab = ambilKitab((int)$data['nama_kitab']);

        $post_title = str_replace('_', ' ', "$nama_kitab $hno");
        $post_excerpt = str_replace(' ', '_', $post_title);
        $post_content = str_replace("'", "''", "$nass<br>$terjemah");

        //nah ini nama-nama kolom dalam tabel 'wp_posts'
        $query = "INSERT INTO `wp_posts` (
            `post_author`, 
            `post_date`, 
            `post_date_gmt`, 
            `post_content`, 
            `post_title`, 
            `post_excerpt`, 
            `post_status`, 
            `comment_status`, 
            `ping_status`, 
            `post_password`, 
            `post_name`, 
            `to_ping`, 
            `pinged`, 
            `post_modified`, 
            `post_modified_gmt`, 
            `post_content_filtered`, 
            `post_parent`, 
            `guid`, 
            `menu_order`, 
            `post_type`, 
            `post_mime_type`, 
            `comment_count`
            ) 
            VALUES (
            $author,
            '$date',
            '$date',
            '$post_content',
            '$post_title',
            '$post_excerpt',
            'publish',
            'open',
            'open',
            '',
            '$bid',
            '',
            '',
            '$date',
            '$date',
            '',
            0,
            'https://shamela.data.blog/?p=$nama_kitab$hno',
            0,
            'post',
            '',
            0
            )";
        if (mysqli_query($testdata_koneksi, $query)) echo "$bid "; //aktifkan ini untuk mengeksekusi
        $time++;
    }
}


function ambilKitab($no)
{
    $kitabs = ['Shahih_Bukhari', 'Shahih_Muslim', 'Sunan_Abu_Daud', 'Sunan_Tirmidzi', 'Sunan_Nasai', 'Sunan_Ibnu_Majah', 'Musnad_Darimi', 'Muwatho_Malik', 'Musnad_Ahmad', 'Sunan_Daraquthni', 'Musnad_Syafii', 'Mustadrak_Hakim', 'Shahih_Ibnu_Khuzaimah', 'Shahih_Ibnu_Hibban', 'Bulughul_Maram', 'Riyadhus_Shalihin', 'Al_Adabul_Mufrad', 'Mushannaf_Ibnu_Abi_Syaibah', 'Mushannaf_Abdurrazzaq', 'Musnad_Abu_Yala', 'Musnad_Bazzar', 'Mujam_Thabarani_Shaghir', 'Mujam_Thabarani_Awsath', 'Mujam_Thabarani_Kabir', 'Hilyatul_Aulia', 'Doa_Thabarani', 'Arbain_Nawawi_I', 'Arbain_Nawawi_II', 'Akhlak_Rawi_Khatib', 'Mukhtashar_Qiyamullail_Marwazi', 'Syuabul_Iman_Baihaqi', 'Shahih_Ibnu_Khuzaimah_Arab', 'Shahih_Ibnu_Hibban_Arab', 'Riyadhus_Shalihin_Arab', 'Shahih_Adabul_Mufrad_Terjemah', 'Silsilah_Shahihah_Terjemah', 'Bulughul_Maram_Arab', 'Bulughul_Maram_Tahqiq_Fahl', 'Sunan_Baihaqi_Shaghir', 'Sunan_Baihaqi_Kabir', 'Targhib_wat_Tarhib_Mundziri', 'Majmauz_Zawaid', 'Fathul_Bari_Ibnu_Hajar', 'Syarh_Shahih_Muslim_Nawawi', 'Aunul_Mabud', 'Tuhfatul_Ahwadzi', 'Hasyiatus_Sindi_Nasai', 'Hasyiatus_Sindi_Ibnu_Majah', 'Tamhid_Ibnu_Abdil_Barr', 'Mirqatul_Mafatih_Ali_Al_Qari', 'Syarah_Arbain_Nawawi_Ibnu_Daqiq', 'Penjelasan_Hadis_Pilihan', 'Faidhul_Qadir', 'Mustadrak_Hakim_Arab', 'Silsilah_Shahihah_Albani'];
    return $kitabs[$no - 1];
}

/*  */
