<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('vue_template.php');
require_once('modele_template.php');

class Cont_template {
    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new Modele_template();
        $this->vue = new Vue_template();
    }

    public function print_content() {
        return $this->vue->close_buffer();
    }
}
?>