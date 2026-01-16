<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('connexion.php');


class ModeleBuvette extends connexion
{

    public function getListe(string $login)
    {
        $sql = '
        SELECT *
        FROM bar
        WHERE id_bar IN (
            SELECT bar_associe
            FROM role
            WHERE login_utilisateur = :login
        )
    ';

        $stmt = self::$bdd->prepare($sql);
        $stmt->bindValue(':login', $login);
        $stmt->execute();

        return $stmt->fetchAll();
    }
    public function getRole($login_utilisateur){
        $sql = 'SELECT role_bar 
        FROM role 
        WHERE login_utilisateur = :login_utilisateur';

        $stmt = self::$bdd->prepare($sql);
        $stmt->bindValue(':login_utilisateur', $login_utilisateur);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

}
