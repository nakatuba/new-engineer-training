<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

if (!isset($_POST['name'])) {
    http_response_code(400);
    exit;
}

$name = $_POST['name'];
echo "<h1>Hello, {$name}!<h1>";
