SELECT sum(quantite)
FROM produit_commande
         INNER JOIN commande ON produit_commande.id_commande = commande.id_commande
         INNER JOIN produit ON produit.id_produit = produit_commande.id_produit
WHERE bar_associe = 1

  AND produit_commande.id_produit =2;