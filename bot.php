<?php

define('BASEPATH', __DIR__);

date_default_timezone_set('Asia/Jakarta');
ini_set('date.timezone', 'Asia/Jakarta');

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require_once BASEPATH . "/vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'Harus pakai POST';
    return;
}

/* Get library */

use Pasya\OneSender\AutoReply;

/* Get config */

$config = require_once BASEPATH . "/config.php";
/* create inbox */
$bot = new AutoReply($config);


/* ------------------------- UBAH DARI SINI ------------------------- */
/* set reply */

// $bot->reply("
// Bila pengiriman anda adalah *REGULER* wajib melakukan menanyakan admin ongkos kirim ke kota anda
// dengan cara kirim pesan *CHATADMIN*

// Silahkan Lakukan Pembayaran ke rekening dibawah ini : 

// Sebesar 105.519 dikali jumlah barang yang di pesan

// dan jumlah ongkir yang di berikan admin kepada anda

// contoh : 

// pembelian baju muslim dengan code BM090401 - 1 buah, BM090402 - 1 buah, Maka total adalah 2 buah, maka _105.519x2_ dengan total pembayaran *Rp. 211.038,-*

// BCA 
// GALANG YOGA PRADANA
// _*2381158057*_

// Bila Ingin Pembayaran Lainya Bisa chat *MetodeLain*

// Lampirkan Bukti Pembayaran dan jumlah pesanan

// Lalu Ketik *KonfirmasiPembayaran*

// Bila pengiriman anda adalah *COD* maka cukup kirim

// *SayaCODGan*

// dan tunggu resi dari admin ya

// *RETURN BARANG COD WAJIB MELAKUKAN UNBOXING VIDEO ATAU VIDEO CALL BERSAMA ADMIN*
// ");

$bot->onMessage('Benar', function ($bot, $request) {
    $bot->reply("Termakasih Atas Konfirmasinya, Orderan Anda Menggunakan Metode *COD* atau *REGULER* ?    ");
});

$bot->alias('benar', 'Benar');

$bot->onMessage('COD', function ($bot, $request) {
    $bot->reply("Untuk *COD* harap menunggu konfirmasi dari admin untuk resi yang akan di kirimkan ke alamat anda.");
});

$bot->onMessage('REGULER', function ($bot, $request) {
    $bot->reply("Untuk Pemesanan *Reguler* anda perlu membayar terlebih dahulu melalui tranfer bank dan virtual account atau ke bank bca
    BANK BCA
    _2381158057_

    BANK BRI 
    _88810082134832652_
    
    BANK MANDIRI 
    _89508082134832652_
    
    BANK MANDIRI 
    _852808082134832652_
    
    BANK BSI / BANK SYARIAH INDONESIA 
    _852808082134832652_

    Semua rekining di atas adalah atasnama *GALANG YOGA PRADANA*
    
    Lampirkan Bukti Pembayaran dan jumlah pesanan
    
    Lalu Ketik *KonfirmasiPembayaran*
");
});

$bot->alias('cod', 'COD');
$bot->alias('Cod', 'COD');
$bot->alias('reguler', 'REGULER');
$bot->alias('Reguler', 'REGULER');


$bot->onMessage('MetodeLain', function ($bot, $request) {

    $bot->reply("
UNTUK PEMBAYARAN LAINYA BISA MENGGUNAKAN VIRTUAL ACCOUNT DIBAWAH INI

BANK BRI 
_88810082134832652_

BANK MANDIRI 
_89508082134832652_

BANK MANDIRI 
_852808082134832652_

BANK BSI / BANK SYARIAH INDONESIA 
_852808082134832652_

Lampirkan Bukti Pembayaran dan jumlah pesanan

Lalu Ketik *KonfirmasiPembayaran*
");
});

$bot->alias('metodelain', 'MetodeLain');
$bot->alias('Metodelain', 'MetodeLain');


$bot->onMessage('Salah', function ($bot, $request) {

    $bot->reply("
Silahkan Ketik *PesanUlang* untuk memulai pesanan ulang
");
});

$bot->alias('salah', 'Salah');

$bot->onMessage('SayaCODGan', function ($bot, $request) {
    $bot->forwardPesananCODBaru('6282134832652');
});

$bot->alias('sayacodgan', 'SayaCODGan');
$bot->alias('SayaCodGan', 'SayaCODGan');
$bot->alias('Sayacodgan', 'SayaCODGan');

$bot->onMessage('CHATADMIN', function ($bot, $request) {
    $bot->forwardCustomerNeedHelp('6282134832652');
});

$bot->alias('Chatadmin', 'CHATADMIN');
$bot->alias('ChatAdmin', 'CHATADMIN');
$bot->alias('chatadmin', 'CHATADMIN');

$bot->onMessage('PesanUlang', function ($bot, $request) {
    $bot->reply("
Nama Penerima : 
Alamat Penerima :
Nomor Telepon : 
Kode Pos : 
Kelurahan:
Kecamatan :
Quantity :
Kode Barang : 
Jumlah Barang : 

Note : Isi Detail Pesanan Dengan Benar lalu Ketik *Benar* jika sudah benar, jika belum benar ketik *Salah*
");
});

$bot->alias('pesanulang', 'PesanUlang');


$bot->onMessage('KonfirmasiPembayaran', function ($bot, $request) {
    $bot->forwardKonfirmasiPembayaran('6285172412818');
});

$bot->alias('konfirmasipembayaran', 'KonfirmasiPembayaran');



// $bot->onMessage('/text', function($bot, $request) {
//     /**
//      * $request = InboxMessage {
//      *    from: "081200000001@s.whatsapp.net"
//      *    sender: "081200000001@s.whatsapp.net"
//      *    phone: "081200000001"
//      *    type: "text"
//      *    content: "/text"
//      *    contentId: null
//      * }
//      */
//     $data = ['nama' => 'Big Bos'];
//     $bot->reply("Apa kabar, {{nama}}?", $data);
// });

/* Forward message (hanya untuk pesan text) */
$bot->onMessage('/text', function ($bot, $request) {
    /**
     * $request = InboxMessage {
     *    from: "081200000001@s.whatsapp.net"
     *    sender: "081200000001@s.whatsapp.net"
     *    phone: "081200000001"
     *    type: "text"
     *    content: "/text"
     *    contentId: null
     * }
     */
    $bot->forward('628120000002');
});


// $bot->onMessage('/gambar', function($bot, $request) {
//     $data = ['nama' => 'Big Bos'];
//     $bot->replyImage("https://i.postimg.cc/xTGP7ck2/dummy.jpg", "Apa kabar?", $data);
// });

// /* Trigger: `menu-3` sama dengan `/gambar` */
// $bot->alias('menu-3', '/gambar');


// $bot->onMessage('/menu', function($bot, $request) {
//     $buttons = [
//         'menu-1' => '/menu1', 
//         'menu-2' => '/menu2', 
//         'menu-3' => '/menu3', 
//     ];

//     $header = 'Pilihan menu';
//     $footer = 'Dikirim dengan OneSender';

//     $data = ['nama' => 'Big Bos'];

//     $bot->replyButton("Silahkan pilih demo menu di bawah ini.", $buttons, $data, $header, $footer);
// });

// $bot->alias('menu', '/menu');

// $bot->onMessage('menu', function ($bot, $request) {
//     $buttons = [
//         'menu1-1' => 'Argentina',
//         'menu1-2' => 'Senegal',
//     ];

//     $header = 'Kandidat Juara Piala Dunia';
//     $footer = 'Dikirim dengan OneSender';

//     $data = ['nama' => 'Big Bos'];

//     $bot->replyButton("Mana tim favorit kamu, {{nama}}?", $buttons, $data, $header, $footer);
// });

// $bot->alias('/menu-1', 'menu-1');


// $bot->onMessage('menu1-1', function($bot, $request) {
//     $data = ['nama' => 'Big Bos'];
//     $bot->reply("Peluang Argentina 50%, {{nama}}.", $data);
// });

// $bot->onMessage('menu1-2', function($bot, $request) {
//     $data = ['nama' => 'Big Bos'];
//     $bot->reply("Anda perlu banyak berdoa agar tim ini juara, {{nama}}.", $data);
// });


// /* Menu ke-2*/
// $bot->onMessage('menu-2', function($bot, $request) {
//     $buttons = [
//         'menu2-1' => 'Emyu', 
//         'menu2-2' => 'City', 
//     ];

//     $header = 'EPL Team';
//     $footer = 'Dikirim dengan OneSender';

//     $data = ['nama' => 'Big Bos'];

//     $bot->replyButton("Tim Merah atau Biru?", $buttons, $data, $header, $footer);
// });

// $bot->alias('/menu-2', 'menu-2');


// $bot->onMessage('menu2-1', function($bot, $request) {
//     $data = ['nama' => 'Big Bos'];
//     $bot->reply("Saya no komen, {{nama}}.", $data);
// });

// $bot->onMessage('menu2-2', function($bot, $request) {
//     $data = ['nama' => 'Big Bos'];
//     $bot->reply("Sudah tradisinya juara, {{nama}}.", $data);
// });


// $bot->onMessage('/list', function($bot, $request) {
//     $button = 'Menu';
//     $options = [
//         [
//             'title' => 'EPL Teams',
//             'buttons' => [
//                 ['id'=> 'option-1-1', 'button' => 'City',                'description' => 'Manchester blue'],
//                 ['id'=> 'option-1-2', 'button' => 'Liverpool',           'description' => 'Liverpool red'],
//                 ['id'=> 'option-1-3', 'button' => 'Chealsea',            'description' => 'London blue'],
//                 ['id'=> 'option-1-4', 'button' => 'Tottenham Hotspurs',  'description' => 'London navy'],
//             ]
//         ],
//         [
//             'title' => 'LaLiga Teams',
//             'buttons' => [
//                 ['id'=> 'option-2-1', 'button' => 'Real Madrid', 'description' => 'Madrid white'],
//                 ['id'=> 'option-2-2', 'button' => 'Atletico', 'description' => 'Madrid red and blue'],
//                 ['id'=> 'option-2-3', 'button' => 'Barcelona', 'description' => 'Catalan team'],
//                 ['id'=> 'option-2-4', 'button' => 'Osasune', 'description' => 'No comment'],
//             ]
//         ],

//     ];

//     $header = 'Best soccer team';
//     $footer = 'Dikirim dengan OneSender';

//     $data = ['nama' => 'Big Bos'];

//     $bot->replyList(
//         "Mana tim favorit kamu, {{nama}}?", 
//         $button, 
//         $options, 
//         $data, 
//         $header, 
//         $footer
//     );
// });
// $bot->alias('menu-3', '/list');

// $bot->onMessage('option-1-1', function($bot, $request) {
//     $data = ['nama' => 'Big Bos'];
//     $bot->reply("Pilihan bagus, {{nama}}.", $data);
// });
// $bot->alias('option-1-2', 'option-1-1');
// $bot->alias('option-1-3', 'option-1-1');
// $bot->alias('option-1-4', 'option-1-1');
// $bot->alias('option-2-1', 'option-1-1');
// $bot->alias('option-2-2', 'option-1-1');
// $bot->alias('option-2-3', 'option-1-1');
// $bot->alias('option-2-4', 'option-1-1');

// /* Set any message 
//  * digunakan untuk membalas apapun pesan masuk ke inbox. 
//  * tidak harus diisi. opsional.
//  */
// $bot->onAnyMessage(function($bot, $request) {

//     /* Kirim pesan jika di luar jam kerja: 8 - 16 */
//     $hour = (int) date('H');
//     if ($hour < 8 || $hour > 16 ) {
//         $bot->reply("Terima kasih kami akan membalas secepatnya.");
//     }

// });

/* ------------------------- UBAH SAMPAI SINI ------------------------- */


/* Kirim pesan reply . Baris ini wajib ada. */
$bot->dispatch();
