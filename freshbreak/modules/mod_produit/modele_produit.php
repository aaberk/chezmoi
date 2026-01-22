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

    public function ajouterProduit($nom, $prixAchat, $prixVente, $fournisseur) {
        $reqCheck = self::$bdd->prepare("SELECT COUNT(*) FROM fournisseur WHERE id_fournisseur = :id");
        $reqCheck->bindParam(':id', $fournisseur);
        $reqCheck->execute();

        if ($reqCheck->fetchColumn() == 0) {
            $reqFourn = self::$bdd->prepare("
                INSERT INTO fournisseur (id_fournisseur, nom, email, telephone) 
                VALUES (:id, 'Fournisseur temporaire', 'temp@temp.com', '0000000000')
                ON DUPLICATE KEY UPDATE nom = nom
            ");
            $reqFourn->bindParam(':id', $fournisseur);
            $reqFourn->execute();
        }

        $req = self::$bdd->prepare("
            INSERT INTO produit (nom_produit, prix_achat, prix_vente, fournisseur)
            VALUES (:nom, :pa, :pv, :fournisseur)
        ");
        $req->bindParam(':nom', $nom);
        $req->bindParam(':pa', $prixAchat);
        $req->bindParam(':pv', $prixVente);
        $req->bindParam(':fournisseur', $fournisseur);
        $req->execute();
    }

    public function getProduits() {
        $req = self::$bdd->query("
            SELECT p.nom_produit, p.prix_vente
            FROM produit p
        ");
        return $req->fetchAll();
    }
}