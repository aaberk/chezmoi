<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('connexion.php');

class Modele_produit extends connexion {

    public function produitExiste($nom) {
        $req = self::$bdd->prepare(
            "SELECT COUNT(*) FROM produit WHERE nom_produit = :nom"
        );
        $req->bindParam(':nom', $nom);
        $req->execute();
        return $req->fetchColumn() > 0;
    }

    public function ajouterProduit($nom, $prixAchat, $prixVente, $idFournisseur) {
        $req = self::$bdd->prepare("
        INSERT INTO produit (nom_produit, prix_achat, prix_vente, fournisseur)
        VALUES (:nom, :pa, :pv, :f)
    ");
        $req->execute([
            ':nom' => $nom,
            ':pa' => $prixAchat,
            ':pv' => $prixVente,
            ':f'  => $idFournisseur
        ]);

        return self::$bdd->lastInsertId();
    }

    public function getProduits() {
        $req = self::$bdd->query("
            SELECT nom_produit, prix_vente
            FROM produit
        ");
        return $req->fetchAll();
    }

    public function getProduitsComplets() {
        $req = self::$bdd->query("
        SELECT id_produit, nom_produit, prix_achat
        FROM produit
        ORDER BY nom_produit
    ");
        return $req->fetchAll();
    }

    public function getProduitById($id) {
        $req = self::$bdd->prepare("
        SELECT *
        FROM produit
        WHERE id_produit = :id
    ");
        $req->execute([':id' => $id]);
        return $req->fetch();
    }

    public function getFournisseurs() {
        $req = self::$bdd->query("
            SELECT id_fournisseur, nom
            FROM fournisseur
            ORDER BY nom
        ");
        return $req->fetchAll();
    }

    public function getFournisseurByInfos($nom, $email, $tel) {
        $req = self::$bdd->prepare("
            SELECT * FROM fournisseur
            WHERE nom = :nom AND email = :email AND telephone = :tel
        ");
        $req->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':tel' => $tel
        ]);

        return $req->fetch();
    }

    public function ajouterFournisseur($nom, $email, $tel) {
        $req = self::$bdd->prepare("
            INSERT INTO fournisseur (nom, email, telephone)
            VALUES (:nom, :email, :tel)
        ");
        $req->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':tel' => $tel
        ]);

        return self::$bdd->lastInsertId();
    }

    public function ajouterStock($barId, $produitId, $quantite) {
        $req = self::$bdd->prepare("
        INSERT INTO disponibilite (bar_associe, id_produit, quantite)
        VALUES (:b, :p, :q)
    ");
        $req->execute([
            ':b' => $barId,
            ':p' => $produitId,
            ':q' => $quantite
        ]);
    }

}