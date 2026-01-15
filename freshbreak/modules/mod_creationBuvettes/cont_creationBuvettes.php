<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('vue_creationBuvettes.php');
require_once('modele_creationBuvettes.php');

class ContCreationBuvettes {
    private $vue;
    private $modele;
    public function __construct()
    {
        $this->vue = new VueCreationBuvettes();
        $this->modele = new ModeleCreationBuvettes();
    }

    public function form_ajout_bar()
    {
        if (isset($_POST['nom'])) {

            $nom = trim($_POST['nom']);

            if ($nom == '') {
                $this->vue->message("❌ Le nom de la buvette est obligatoire.");
                return;
            }

            if ($this->modele->nomexist($nom)) {
                $this->vue->message("❌ Cette buvette existe déjà.");
                return;
            }
                 $this->modele->ajouterBuvette($nom);



        } else {
            $this->vue->form_inscription();
        }
    }

    public function print_content() {
        return $this->vue->close_buffer();
    }
}
