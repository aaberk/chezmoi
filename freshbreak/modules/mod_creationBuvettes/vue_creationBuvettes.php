<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once ("./vue_generique.php");

class VueCreationBuvettes extends Vue_generique {
    public function __construct() {
        parent::__construct();
    }

    public function form_inscription() {
        echo '
        <h2>Ajout Buvette</h2>
        <form method="post" action="index.php?module=creationBuvettes">
            <label>Nom Buvette :</label>
            <input type="text" name="nom" required><br>
            <button type="submit">S’inscrire</button>
        </form>';
    }

    public function message($message) {
        echo "<p>$message</p>";
    }
}