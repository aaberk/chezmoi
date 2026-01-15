<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('./vue_generique.php');

class VueGestionProfils extends Vue_generique {
    public function __construct() {
        parent::__construct();
    }

    public function form_ajout() {
        echo '
        <h2>Ajouter un membre à la buvette</h2>
        <form method="post" action="index.php?module=gestion_profils&action=ajouter">
            <label>Login de l\'utilisateur :</label>
            <input type="text" name="login" required><br>

            <label>Rôle :</label>
            <select name="role" required>
                <option value="client">Client</option>
                <option value="barman">Barman</option>
            </select><br>

            <button type="submit">Ajouter</button>
        </form>';
    }

    public function message($texte) {
        echo "<p>$texte</p>";
    }
}
?>