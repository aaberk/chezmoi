<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('vue_connexion.php');
require_once('modele_connexion.php');

class ContConnexion {
    private $vue;
    private $modele;

    public function __construct() {
        $this->vue = new VueConnexion();
        $this->modele = new ModeleConnexion();


    }

    // --- FORMULAIRE D’INSCRIPTION ---
    public function form_inscription() {
        if (isset($_SESSION['login'])) {
            $this->vue->deja_connecte($_SESSION['login']);
        } elseif (!empty($_POST['login']) && !empty($_POST['mdp'])) {
            $login = $_POST['login'];
            $mdp = $_POST['mdp'];

            if ($this->modele->loginExiste($login)) {
                $this->vue->message("❌ Ce login existe déjà.");
            } else {
                $this->modele->ajouterUtilisateur($login, $mdp);
                $this->vue->message("✅ Inscription réussie !");
                echo '<a href="index.php?module=connexion&action=connexion">Se connecter</a>';
            }
        } else {
            $this->vue->form_inscription();
        }
    }

    // --- FORMULAIRE DE CONNEXION ---
    public function form_connexion() {
        if (!isset($_SESSION['login']) && !empty($_POST['login']) && !empty($_POST['mdp'])) {
            $login = $_POST['login'];
            $mdp = $_POST['mdp'];

            if ($this->modele->verifierConnexion($login, $mdp)) {
                $_SESSION['login'] = $login;  // ✅ L’utilisateur est connecté
                $_SESSION['solde'] = $this->modele->getSolde($login);
                header('Location: index.php'); // redirige vers l’accueil
            } else {
                $this->vue->message("❌ Identifiant ou mot de passe incorrect.");
                $this->vue->form_connexion();
            }
        } else if (!isset($_SESSION['login'])) {
            $this->vue->form_connexion();
        }
    }

    // --- DÉCONNEXION ---
    public function deconnexion() {
        session_destroy();
        header('Location: index.php');
        exit;
    }

    public function print_content() {
        return $this->vue->close_buffer();
    }
}
?>

