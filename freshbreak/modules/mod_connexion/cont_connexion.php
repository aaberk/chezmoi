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

    public function form_inscription() {

        if (isset($_SESSION['login'])) {
            $this->vue->deja_connecte($_SESSION['login']);
            return;
        }

        if (!empty($_POST)) {

            if (
                empty($_POST['csrf_token']) ||
                empty($_SESSION['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                //$this->vue->message("Erreur de sécurité.");
                return;
            }

            if (!empty($_POST['login']) && !empty($_POST['mdp'])) {
                $login = $_POST['login'];
                $mdp = $_POST['mdp'];

                if ($this->modele->loginExiste($login)) {
                    $this->vue->message("Ce login existe déjà.");
                } else {
                    $this->modele->ajouterUtilisateur($login, $mdp);
                    $_SESSION['login'] = $login;
                    $_SESSION['solde'] = $this->modele->getSolde($login);
                    $_SESSION['admin'] = false;
                    unset($_SESSION['bar_id']);
                    header('Location: index.php?module=buvettes&action=liste');
                    exit;
                }
            }
        }

        $this->vue->form_inscription();
    }


    public function form_connexion() {
        if (!isset($_SESSION['login']) && !empty($_POST['login']) && !empty($_POST['mdp'])) {
            $login = $_POST['login'];
            $mdp = $_POST['mdp'];

            if ($this->modele->verifierConnexion($login, $mdp)) {
                $_SESSION['login'] = $login;
                $_SESSION['solde'] = $this->modele->getSolde($login);
                if ($this->modele->getAdmin() == $login)
                    $_SESSION['admin'] = true;
                else
                    $_SESSION['admin'] = false;
                unset($_SESSION['bar_id']); // sécurité
                header('Location: index.php?module=buvettes&action=liste');
                exit;
            } else {
                $this->vue->message(" Identifiant ou mot de passe incorrect.");
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

