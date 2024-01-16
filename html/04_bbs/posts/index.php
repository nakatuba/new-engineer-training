<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

if (!isset($_POST['content'])) {
    http_response_code(400);
    exit;
}

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

try {
    $dbh = new PDO('mysql:host=localhost;dbname=mysql', 'vagrant', 'vagrant');

    $user_id = $_SESSION['user_id'];
    $content = $_POST['content'];

    $stmt = $dbh->prepare('insert into posts (user_id, content) values (:user_id, :content)');
    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':content', $content);
    $stmt->execute();
    $user = $stmt->fetch();

    header('Location: /04_bbs/');
} catch (PDOException $e) {
    echo $e->getMessage();
}
