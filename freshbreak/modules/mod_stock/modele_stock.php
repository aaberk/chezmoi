<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('connexion.php');

class ModeleStock extends connexion {

    public function getNomBar($barId) {
        $req = self::$bdd->prepare("
        SELECT nom
        FROM bar
        WHERE id_bar = :id
    ");
        $req->execute([':id' => $barId]);
        return $req->fetchColumn();
    }

    public function getStockParBar($barId) {
        $req = self::$bdd->prepare("
            SELECT p.nom_produit, d.quantite
            FROM disponibilite d
            JOIN produit p ON p.id_produit = d.id_produit
            WHERE d.bar_associe = :bar
            ORDER BY p.nom_produit
        ");
        $req->execute([':bar' => $barId]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoleParBar($login_utilisateur, int $barId){
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

    public function ajouterOuIncrementerStock($barId, $produitId, $quantite) {

        $req = self::$bdd->prepare("
            SELECT quantite
            FROM disponibilite
            WHERE bar_associe = :bar AND id_produit = :prod
        ");
        $req->execute([
            ':bar' => $barId,
            ':prod' => $produitId
        ]);

        if ($req->fetch()) {
            $req = self::$bdd->prepare("
                UPDATE disponibilite
                SET quantite = quantite + :q
                WHERE bar_associe = :bar AND id_produit = :prod
            ");
        } else {
            $req = self::$bdd->prepare("
                INSERT INTO disponibilite (bar_associe, id_produit, quantite)
                VALUES (:bar, :prod, :q)
            ");
        }

        $req->execute([
            ':bar' => $barId,
            ':prod' => $produitId,
            ':q' => $quantite
        ]);
    }
}
