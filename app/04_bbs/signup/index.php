<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo $twig->render('signup.html');
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        http_response_code(400);
        exit;
    }

    session_start();

    try {
        $dbh = new PDO('mysql:host=localhost;dbname=development_db', 'vagrant', 'vagrant');

        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $dbh->prepare('select * from users where username = :username');
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user['username'] === $username) {
            echo $twig->render('error.html', ['detail' => '同じユーザー名がすでに登録済みです']);
        } else {
            $stmt = $dbh->prepare('insert into users (username, password) values (:username, :password)');
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
            $stmt->execute();

            $stmt = $dbh->prepare('select * from users where username = :username');
            $stmt->bindValue(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header('Location: /04_bbs/');
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} else {
    http_response_code(405);
    exit;
}
