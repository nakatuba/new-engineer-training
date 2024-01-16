<?php

session_start();

try {
    $dbh = new PDO('mysql:host=localhost;dbname=mysql', 'vagrant', 'vagrant');

    $stmt = $dbh->prepare('select * from posts order by created_at');
    $stmt->execute();
    $posts = $stmt->fetchAll();

    foreach ($posts as $post) {
        $stmt = $dbh->prepare('select * from users where id = :id');
        $stmt->bindValue(':id', $post['user_id']);
        $stmt->execute();
        $user = $stmt->fetch();

        echo <<<EOM
        <div style="margin-bottom: 1rem">
          {$user['username']}<br />
          {$post['created_at']}<br />
          {$post['content']}<br />
        </div>
        EOM;
    }

    if (isset($_SESSION['user_id'])) {
        $stmt = $dbh->prepare('select * from users where id = :id');
        $stmt->bindValue(':id', $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->fetch();

        echo <<<EOM
        <form action="/04_bbs/logout/" method="post">
          {$user['username']} でログイン中
          <input type="submit" value="ログアウト" />
        </form>
        <form action="/04_bbs/posts/" method="post">
          <textarea name="content"></textarea>
          <input type="submit" value="送信" />
        </form>
        EOM;
    } else {
        echo '<a href="/04_bbs/login/">ログイン</a>';
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
