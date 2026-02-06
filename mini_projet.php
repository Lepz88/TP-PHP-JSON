Vous avez un panier de produits (Tableau multidimensionnel). Vous devez :
Calculer le prix total pour chaque ligne (Prix x Quantité).
Calculer le total général de la facture.
Appliquer une remise (TVA ou Réduction) si besoin.
<?php
// 1. LES DONNÉES (Le "Pluriel")
$produits = [
    ["nom" => "Ordinateur", "prix" => 300000, "qte" => 2],
    ["nom" => "Clavier",    "prix" => 15000,  "qte" => 5],
    ["nom" => "Souris",     "prix" => 5000,   "qte" => 10]
];

// Variable pour stocker le total général (Accumulateur)
$totalGeneral = 0;

echo "<h3>Détails de la facture :</h3>";
echo "<ul>";

// 2. LA BOUCLE (La règle d'or : $produits as $produit)
foreach ($produits as $produit) {
    
    // Calcul du sous-total pour cet article
    $sousTotal = $produit["prix"] * $produit["qte"];
    
    // On ajoute ce sous-total au grand total
    $totalGeneral = $totalGeneral + $sousTotal;
    
    // Affichage propre
    echo "<li>";
    echo $produit["nom"] . " : " . $produit["prix"] . " F x " . $produit["qte"] . " unités ";
    echo "= <strong>" . $sousTotal . " F</strong>";
    echo "</li>";
}

echo "</ul>";
echo "<hr>";
echo "<h3>TOTAL À PAYER : " . $totalGeneral . " F CFA</h3>";
?>