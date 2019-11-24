<?php

require './vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location: /index.html');
    return;
}

$file = $_FILES['audio'];

$fileName = 'upload_' . time();

move_uploaded_file(
    $file['tmp_name'],
    './uploads/' . $fileName . '.wav'
);

exec('ffmpeg -i ./uploads/' . $fileName . '.wav -vn -ac 2 -ar 44100 -ab 128k -acodec wmav2 -f asf ./uploads/' . $fileName . '.mp3');

$url = 'https://content.dropboxapi.com/2/files/upload';

$dropboxParameters = [
    'path' => '/uploads/' . $fileName . '.mp3',
    'mode' => 'add',
    'autorename' => true,
    'mute' => false,
    'strict_conflict' => false
];

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

$header = [
    'Authorization: Bearer ' . getenv('DROPBOX_KEY'),
    'Dropbox-API-Arg: ' . json_encode($dropboxParameters),
    'Content-Type: application/octet-stream',
];

$postData = file_get_contents('./uploads/' . $fileName . '.mp3');

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

$response = curl_exec($ch);
curl_close($ch);
