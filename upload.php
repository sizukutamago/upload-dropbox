<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location: /index.html');
    return;
}

$file = $_FILES['audio'];

move_uploaded_file(
    $file['tmp_name'],
    './uploads/test.wav'
);
