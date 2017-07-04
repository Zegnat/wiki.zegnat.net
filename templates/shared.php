<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $title !== 'Zegnåt' ? $title . ' ¶ ' : '' ?>Zegnåt</title>
        <link rel="stylesheet" href="/style.css">
        <link rel="webmention" href="http://vanderven.se/martijn/mention.php">
    </head>
    <body>
        <main>
            <?= $this->section('main') ?>
        </main>
        <footer>
            <a rel="home" href="/">Take me home</a>
            <?= $this->section('footer') ?>
            <small>Copyrighted 2017– <a href="http://vanderven.se/martijn/" rel="author">Martijn van der Ven</a></small>
        </footer>
    </body>
</html>
