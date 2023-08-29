# Script OneSender Auto Reply

Script sederhana untuk membuat Auto Reply

Dapat mengirim pesan autoreply berupa:
1. text
2. gambar
3. Button reply
4. Button list

Pesan yang tidak disupport:
1. Document
2. Interactive Dev

## Cara Install

1. Extract file `autoresponse-v<versi>.zip`.
2. Ubah file config `config.php`.
3. Sesuaikan rule autoresponse di file `bot.php`.
4. Upload ke website. Contoh: `https://domainsaya.com/autoresponse`. Harap gunakan subfolder unix agar link webhook tidak mudah ditebak.
5. Ubah webhook di dashboard OneSender.

PS:
- Anda dapat rename file `bot.php` dengan nama unik yang Anda inginkan.
- Anda dapat menambah extra keamanan dengan validasi header.

## Contoh

```PHP

/* Get library */
use Pasya\OneSender\AutoReply;

/* Get config */
$config = require_once BASEPATH . "/config.php";
/* create inbox */
$bot = new AutoReply($config);

/* Contoh kirim balasan text */
$bot->onMessage('/text', function($bot, $request) {
    $data = ['bos' => 'Bos Gank'];
    $bot->reply("Apa kabar, {{bos}}?", $data)
       ->printResponse();
});

/* Contoh kirim gambar */
$bot->onMessage('/gambar', function($bot, $request) {
    $data = ['bos' => 'Bos Gank'];
    $bot->replyImage("https://i.postimg.cc/xTGP7ck2/dummy.jpg", "Apa kabar?", $data);
});

/* 
 * Menu ke-1
 * - Gunakan key yang berbeda untuk tiap menu agar tidak overlapping
 *   dengan menu yang lain
 */
$bot->onMessage('/menu1', function($bot, $request) {
    $buttons = [
        'menu1-1' => 'Argentina', 
        'menu1-2' => 'Senegal', 
    ];

    $header = 'Kandidat Juara Piala Dunia';
    $footer = 'Dikirim dengan OneSender';

    $data = ['bos' => 'Bos Gank'];

    $bot->replyButton("Mana tim favorit kamu, {{bos}}?", $buttons, $data, $header, $footer);
});

$bot->onMessage('menu1-1', function($bot, $request) {
    $data = ['bos' => 'Bos Gank'];
    $bot->reply("Peluang Argentina 50%, {{bos}}.", $data)
       ->printResponse();
});

$bot->onMessage('menu1-2', function($bot, $request) {
    $data = ['bos' => 'Bos Gank'];
    $bot->reply("Anda perlu banyak berdoa agar tim ini juara, {{bos}}.", $data)
       ->printResponse();
});


/* Menu ke-2*/
$bot->onMessage('/menu2', function($bot, $request) {
    $buttons = [
        'menu2-1' => 'Emyu', 
        'menu2-2' => 'City', 
    ];

    $header = 'EPL Team';
    $footer = 'Dikirim dengan OneSender';

    $data = ['bos' => 'Bos Gank'];

    $bot->replyButton("Tim Merah atau Biru?", $buttons, $data, $header, $footer);
});

$bot->onMessage('menu2-1', function($bot, $request) {
    $data = ['bos' => 'Bos Gank'];
    $bot->reply("Saya no komen, {{bos}}.", $data)
       ->printResponse();
});

$bot->onMessage('menu2-2', function($bot, $request) {
    $data = ['bos' => 'Bos Gank'];
    $bot->reply("Sudah tradisinya juara, {{bos}}.", $data)
       ->printResponse();
});


/* Kirim pesan reply */
$bot->dispatch();
```

## Change log
**v2.1.0**:
- kirim pesan list
- Ubah exception menjadi return false untuk pesan tidak dikenal# webhook-onesender
# webhook-onesender
