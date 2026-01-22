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

            <input type="hidden" name="csrf_token"
                   value="' . htmlspecialchars($_SESSION['csrf_token']) . '">

            <label>Login de l\'utilisateur :</label>
            <input type="text" name="login" required><br>

            <label>Rôle :</label>
            <select name="role" required>
                <option value="client">Client</option>
                <option value="barman">Barman</option>
            </select><br>

            <button type="submit">Ajouter</button>
        </form>';

        echo '<p><a href="index.php?module=buvettes&action=liste"><button type="button">Retour au menu</button></a></p>';
    }


    public function message($texte) {
        echo "<p>$texte</p>";

        echo '<p><a href="index.php?module=buvettes&action=liste"><button type="button">Retour au menu</button></a></p>';
    }
}
?>