<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
</head>

<body>

    <header id="header" role="banner">
        <nav id="nav" role="navigation">
            <img class="logo" src="http://projet11.local/wp-content/uploads/2023/10/Logo-1.png"
                alt="Logo où il est écrit le nom de Nathalie Mota">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'menu-principal',
                    'menu_id' => 'menu-principal',
                )
            );
            ?>
        </nav>
    </header>