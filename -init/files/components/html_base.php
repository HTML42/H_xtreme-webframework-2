<!DOCTYPE ->
<html>
    <head>
        
    </head>
    <body>
        <?= File::instance('components/header.php')->get_content() ?>
        <?= File::instance('components/navigation.php')->get_content() ?>
        <main>
            <article>##yield##</article>
        </main>
        <?= File::instance('components/footer.php')->get_content() ?>
    </body>
</html>