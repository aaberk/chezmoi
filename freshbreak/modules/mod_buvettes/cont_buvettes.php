<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('vue_buvettes.php');
require_once('modele_buvette.php');

class ContBuvettes {
    private $vue;
    private $modele;
    public function __construct() {
        $this->vue = new VueBuvettes();
        $this->modele = new ModeleBuvette();
    }

    public function menu($login, $barId) {
        $role = $this->modele->getRoleParBar($login, $barId);
        $nomBar = $this->modele->getNomBarById($barId);
        $buvettes = $this->modele->getListe($login);

        if (!$role) {
            $this->vue->message("Aucun rôle associé à cette buvette.");
            return;
        }

        $method = 'menu_' . $role;

        if (!method_exists($this->vue, $method)) {
            $this->vue->message("Menu indisponible pour ce rôle.");
            return;
        }

        $this->vue->$method($nomBar,$buvettes);
    }

    public function liste($login){
        if (isset($_SESSION['admin']) && $_SESSION['admin']) {
            $buvettes = $this->modele->getAllBars();
        } else {
            $buvettes = $this->modele->getListe($login);
        }

        $this->vue->afficher_buvette($buvettes);
    }

    public function afficherStock(){}

    public function afficherBilan(){}

    public function payer(){
    }

    public function choix(){
        $this->vue->choice();
    }

    public function voir_produits() {
        if (!isset($_SESSION['bar_id'])) {
            echo '<p>Veuillez d\'abord sélectionner une buvette.</p>';
            return;
        }

        $produits = $this->modele->getProduitsDisponibles($_SESSION['bar_id']);
        $this->vue->afficher_produits_disponibles($produits);
    }

    public function print_content() {
        return $this->vue->close_buffer();
    }

    public function retour(){}


}