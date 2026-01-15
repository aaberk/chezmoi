<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('connexion.php');


class ModeleCreationBuvettes extends connexion {
    public function nomexist($nom) {
        $req = self::$bdd->prepare("SELECT COUNT(*) FROM bar WHERE nom = :nom");
        $req->bindParam(':nom', $nom);
        $req->execute();
        return $req->fetchColumn() > 0;
    }
        public function ajouterBuvette($nom)
        {
            $req = self::$bdd->prepare("INSERT INTO bar (nom) VALUES (:nom)");
            $req->bindParam(':nom', $nom);
            $req->execute();

            $idBar = self::$bdd->lastInsertId();

            $gerant = $_SESSION['login'];

            $req = self::$bdd->prepare("
        INSERT INTO role (login_utilisateur, bar_associe, role_bar)
        VALUES (:login_utilisateur, :bar_associe, 'gérant')
    ");
            $req->bindParam(':login_utilisateur', $gerant);
            $req->bindParam(':bar_associe', $idBar);
            $req->execute();
        }



}
