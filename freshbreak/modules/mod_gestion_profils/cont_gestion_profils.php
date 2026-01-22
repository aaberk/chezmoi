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
        if (
            empty($_POST['csrf_token']) ||
            empty($_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {

            return;
        }

        if (empty($_POST['login']) || empty($_POST['role'])) {
            $this->vue->message("Données manquantes.");
            $this->vue->form_ajout();
            return;
        }

        $login = trim($_POST['login']);
        $role  = trim($_POST['role']);
        $bar_id = $_SESSION['bar_id'] ?? null;

        if (!$bar_id) {
            $this->vue->message("Aucune buvette sélectionnée.");
            return;
        }

        if (!$this->modele->utilisateurExiste($login)) {
            $this->vue->message("Cet utilisateur n'existe pas.");
            $this->vue->form_ajout();
            return;
        }

        if ($this->modele->estDejaMembreBuvette($login, $bar_id)) {
            $this->vue->message("Cet utilisateur est déjà membre de cette buvette.");
            $this->vue->form_ajout();
            return;
        }


        $this->modele->ajouterMembreBuvette($login, $bar_id,$role);

        unset($_SESSION['csrf_token']); // token à usage unique

        $this->vue->message("Membre ajouté avec succès.");
    }

    public function print_content() {
        return $this->vue->close_buffer();
    }
}
