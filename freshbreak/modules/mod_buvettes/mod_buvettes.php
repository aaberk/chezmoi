<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('cont_buvettes.php');



class ModBuvettes {
    private $controleur;

    public function __construct() {
        $this->controleur = new ContBuvettes();
    }

    public function exec()
    {
        // action unique
        $action = $_GET['action'] ?? 'choix';

        // mémorisation du bar sélectionné
        if (isset($_GET['bar_id'])) {
            $_SESSION['bar_id'] = intval($_GET['bar_id']);

        }

        switch ($action) {

            case 'choix':
                $this->controleur->choix();
                break;

            case 'rejoindre_buvette':
                header("Location: index.php?module=rejoindreBuvette&action=show_form");
                break;

            case 'liste':
                $this->controleur->liste($_SESSION['login']);
                break;

            case 'stock':
                $this->controleur->afficherStock($_SESSION['bar_id'] ?? null);
                break;

            case 'bilan':
                $this->controleur->afficherBilan($_SESSION['bar_id'] ?? null);
                break;

            case 'payer':
                $this->controleur->payer($_SESSION['bar_id'] ?? null);
                break;

            case 'voir_produits':
                $this->controleur->voir_produits();
                break;

            case 'changer':
                unset($_SESSION['bar_id']);
                header('Location: index.php?module=buvettes&action=liste');
                exit;

            default:
                echo '<p>Action inconnue</p>';
                $this->controleur->choix();
                break;
        }

        // menu affiché uniquement si un bar est sélectionné
        //TODO: à débattre comme comportement
        //TODO: revoir le comportement de changer de bar qui permet pas la demande de rejoindre
        //TODO: le bandeau qui montre dans quel bar on est en haut à gauche
        //TODO: notif à côté de l'inbox
        if (isset($_SESSION['bar_id'])) {
            $this->controleur->menu($_SESSION['login'], $_SESSION['bar_id']);
        }
    }

    public function print_content() {
        return $this->controleur->print_content();
    }
}