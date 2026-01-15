<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('vue_gestion_profils.php');
require_once('modele_gestion_profils.php');

class ContGestionProfils {
    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new ModeleGestionProfils();
        $this->vue = new VueGestionProfils();
    }

    public function formAjoutMembre() {
        if (isset($_SESSION['login'])) {
            $this->vue->form_ajout();
        } else {
            $this->vue->message("Vous devez être connecté.");
        }
    }

    public function ajouterMembre() {
        if (!isset($_POST['login']) || !isset($_POST['role'])) {
            $this->vue->message("Données manquantes.");
            $this->vue->form_ajout();
            return;
        }

        $login = trim($_POST['login']);
        $role = trim($_POST['role']);
        $bar_id = isset($_SESSION['bar_id']) ? $_SESSION['bar_id'] : null;

        if (!$bar_id) {
            $this->vue->message("Aucune buvette sélectionnée.");
            return;
        }

        if (!$this->modele->utilisateurExiste($login)) {
            $this->vue->message("L'utilisateur '$login' n'existe pas.");
            $this->vue->form_ajout();
            return;
        }

        if ($this->modele->estDejaMembreBuvette($login, $bar_id)) {
            $this->vue->message("Cet utilisateur est déjà membre de cette buvette.");
            $this->vue->form_ajout();
            return;
        }

        $this->modele->ajouterMembreBuvette($login, $bar_id, $role);
        $this->vue->message("L'utilisateur a été ajouté avec le rôle '$role'.");
        echo '<a href="index.php?module=gestion_profils&action=form">Ajouter un autre membre</a>';
    }

    public function print_content() {
        return $this->vue->close_buffer();
    }
}
?>