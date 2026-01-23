<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>FreshBreak</title>
        <link rel="stylesheet" href="template.css">
    </head>

    <body>
        <header>
            <div class="header-top">
                <h1>FreshBreak</h1>
                <?php echo $connexion_info->affiche(); ?>
            </div>
            <nav>
                <?php echo $menu_nav->affiche(); ?>
            </nav>
        </header>

        <main>
            <?php echo $template_content; ?>
        </main>

        <footer>

        </footer>
    </body>
</html>


