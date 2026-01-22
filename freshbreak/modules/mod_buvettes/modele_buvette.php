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

    public function getAllBars() {
        $sql = 'SELECT * FROM bar ORDER BY nom';
        $stmt = self::$bdd->query($sql);
        return $stmt->fetchAll();
    }

    public function getRoleParBar($login_utilisateur, $barId){
        $sql = 'SELECT role_bar 
        FROM role 
        WHERE login_utilisateur = :login_utilisateur
        AND bar_associe = :bar';

        $stmt = self::$bdd->prepare($sql);
        $stmt->execute([
            ':login_utilisateur' => $login_utilisateur,
            ':bar' => $barId
        ]);


        return $stmt->fetchColumn();
    }

    public function getNomBarById(int $barId) {
        $sql = 'SELECT nom FROM bar WHERE id_bar = :id';
        $stmt = self::$bdd->prepare($sql);
        $stmt->execute([':id' => $barId]);
        return $stmt->fetchColumn();
    }

    public function getProduitsDisponibles($bar_id) {
        $sql = 'SELECT p.nom_produit, p.prix_vente
                FROM produit p
                JOIN disponibilite d ON p.id_produit = d.id_produit
                WHERE d.bar_associe = :bar_id AND d.quantite > 0
                ORDER BY p.nom_produit';

        $stmt = self::$bdd->prepare($sql);
        $stmt->bindValue(':bar_id', $bar_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

}