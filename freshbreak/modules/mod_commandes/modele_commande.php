<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('connexion.php');

class ModeleCommande extends connexion {

    public function getClients($bar_id) {
        $req = self::$bdd->prepare("
            SELECT u.login, u.solde FROM utilisateur u
            JOIN role r ON u.login = r.login_utilisateur
            WHERE r.bar_associe = :bar_id AND r.role_bar = 'client'
        ");
        $req->bindParam(':bar_id', $bar_id);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduits($bar_id) {
        $req = self::$bdd->prepare("
            SELECT p.id_produit, p.nom_produit, p.prix_vente, s.quantite
            FROM produit p
            JOIN stock s ON p.id_produit = s.id_produit
            WHERE s.bar_associe = :bar_id AND s.quantite > 0
        ");
        $req->bindParam(':bar_id', $bar_id);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduit($id) {
        $req = self::$bdd->prepare("SELECT * FROM produit WHERE id_produit = :id");
        $req->bindParam(':id', $id);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function getSolde($login) {
        $req = self::$bdd->prepare("SELECT solde FROM utilisateur WHERE login = :login");
        $req->bindParam(':login', $login);
        $req->execute();
        return $req->fetch()[0];
    }

    public function creerCommande($login, $bar_id) {
        $req = self::$bdd->prepare("
            INSERT INTO commande (date_commande, login_utilisateur, bar_associe)
            VALUES (CURDATE(), :login, :bar_id)
        ");
        $req->bindParam(':login', $login);
        $req->bindParam(':bar_id', $bar_id);
        $req->execute();
        return self::$bdd->lastInsertId();
    }

    public function ajouterLigne($id_commande, $id_produit, $quantite, $prix) {
        $req = self::$bdd->prepare("
            INSERT INTO produit_commande (id_commande, id_produit, quantite, prix)
            VALUES (:id_commande, :id_produit, :quantite, :prix)
        ");
        $req->bindParam(':id_commande', $id_commande);
        $req->bindParam(':id_produit', $id_produit);
        $req->bindParam(':quantite', $quantite);
        $req->bindParam(':prix', $prix);
        $req->execute();
    }

    public function debiterSolde($login, $montant) {
        $req = self::$bdd->prepare("
            UPDATE utilisateur SET solde = solde - :montant WHERE login = :login
        ");
        $req->bindParam(':login', $login);
        $req->bindParam(':montant', $montant);
        $req->execute();
    }

    public function diminuerStock($id_produit, $bar_id, $quantite) {
        $req = self::$bdd->prepare("
            UPDATE stock SET quantite = quantite - :quantite
            WHERE id_produit = :id_produit AND bar_associe = :bar_id
        ");
        $req->bindParam(':id_produit', $id_produit);
        $req->bindParam(':bar_id', $bar_id);
        $req->bindParam(':quantite', $quantite);
        $req->execute();
    }
}
?>