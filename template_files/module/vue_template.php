<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('./vue_generique.php');
class Vue_template extends Vue_Generique {
    public function __construct() {
        parent::__construct();
    }

}
?>