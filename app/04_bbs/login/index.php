<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo $twig->render('login.html');
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        http_response_code(400);
        exit;
    }

    session_start();

    try {
        $dbh = new PDO('mysql:host=localhost;dbname=mysql', 'vagrant', 'vagrant');

        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $dbh->prepare('select * from users where username = :username');
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: /04_bbs/');
        } else {
            echo $twig->render('error.html', ['detail' => 'ユーザー名またはパスワードが正しくありません']);
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} else {
    http_response_code(405);
    exit;
}
