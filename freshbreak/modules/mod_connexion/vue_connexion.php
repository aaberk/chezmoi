<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once ("./vue_generique.php");

class VueConnexion extends Vue_generique {
    public function __construct() {
        parent::__construct();
    }

    public function form_inscription() {
        echo '
        <h2>Inscription</h2>
        <form method="post" action="index.php?module=connexion&action=inscription">
            <label>Login :</label>
            <input type="text" name="login" required><br>
            <label>Mot de passe :</label>
            <input type="password" name="mdp" required><br>
            <button type="submit">S’inscrire</button>
        </form>';
    }



    public function form_connexion() {
        echo '
        <h2>Connexion</h2>
        <form method="post" action="index.php?module=connexion&action=connexion">
            <label>Login :</label>
            <input type="text" name="login" required><br>
            <label>Mot de passe :</label>
            <input type="password" name="mdp" required><br>
            <button type="submit">Se connecter</button>
            <p>
    Pas encore inscrit ?
    <a href="index.php?module=connexion&action=inscription">
        Créer un compte
    </a>
</p>

        </form>';
    }

    public function deja_connecte($login) {
        echo "<p>Vous êtes déjà connecté sous l’identifiant <b>$login</b>.</p>";
        echo '<a href="index.php?module=connexion&action=deconnexion">Se déconnecter</a>';
    }

    public function message($texte) {
        echo "<p>$texte</p>";
    }
}
?>

