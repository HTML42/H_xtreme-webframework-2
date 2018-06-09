<!DOCTYPE ->
<html>
    <head>
        <meta charset="utf-8" />
        <?= File::i('components/html_head.php')->get_content() ?>
        
    </head>
    <body>
        <?= File::i('components/header.php')->get_content() ?>
        <main>
            <article>##yield##</article>
        </main>
        <?= File::i('components/footer.php')->get_content() ?>
        <?= File::i('components/js_handler.php')->get_content() ?>
    </body>
</html>