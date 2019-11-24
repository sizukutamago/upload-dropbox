<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location: /index.html');
    return;
}

var_dump($_FILES);
