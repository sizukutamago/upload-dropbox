<?php

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
