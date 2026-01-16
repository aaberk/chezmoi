<?php
    // DÉMARRER LA SESSION EN PREMIER — absolument tout en haut
    session_start();

    define('APP_SECURE', true);
    require_once('connexion.php');
    require_once('composants/connexion_info/comp_connexion_info.php');
    require_once('composants/menu_nav/comp_menu_nav.php');

    // Initialisation de la connexion à la BDD
    connexion::initConnexion();

    // Détermination du module
    $module = isset($_GET['module']) ? $_GET['module'] : 'connexion';

    switch ($module) {
        case 'creationBuvettes':
            require_once('modules/mod_creationBuvettes/mod_creationBuvettes.php');
            $mod = new ModCreationBuvettes();
            $mod->exec();
            $template_content = $mod->print_content();
            break;

        case 'connexion':
            require_once('modules/mod_connexion/mod_connexion.php');
            $mod = new ModConnexion();
            $mod->exec();
            $template_content = $mod->print_content();
            break;

        case 'solde_refill':
            require_once('modules/mod_solde_refill/mod_solde_refill.php');
            $mod = new ModSoldeRefill();
            $mod->exec();
            $template_content = $mod->print_content();
            break;
        case 'buvettes':
            require_once('modules/mod_buvettes/mod_buvettes.php');
            $mod = new ModBuvettes();
            $mod->exec();
            $template_content = $mod->print_content();
            break;
        case 'stock':
            require_once('modules/mod_stock/mod_stock.php');
            $mod = new ModStock();
            $mod->exec();
            $template_content = $mod->print_content();
            break;
        case 'gestion_profils':
            require_once('modules/mod_gestion_profils/mod_gestion_profils.php');
            $mod = new ModGestionProfils();
            $mod->exec();
            $template_content = $mod->print_content();
            break;
        case 'produit':
            require_once('modules/mod_produit/mod_produit.php');
            $mod = new Modproduit();
            $mod->exec();
            $template_content = $mod->print_content();
            break;

        case 'commande':
            require_once('modules/mod_commandes/mod_commande.php');
            $mod = new ModCommande();
            $mod->exec();
            $template_content = $mod->print_content();
            break;

    }

    $connexion_info = new Comp_connexion_info();
    $menu_nav = new Comp_menu_nav();
    require_once ("template.php");
?>

