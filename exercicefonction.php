<?php
    $nom_fichier = "personne.json";
    $donnees_brutes = file_get_contents($nom_fichier);
    $personnes = json_decode($donnees_brutes, true);

    
    if (isset($_POST['btn_ajouter'])) {
        $nouvel_inscrit = [
            "nom" => $_POST['nom'],
            "prenom" => $_POST['prenom'],
            "email" => $_POST['email']
        ];
        $personnes[] = $nouvel_inscrit;
        file_put_contents($nom_fichier, json_encode($personnes));
    }


     On regarde si "id_suppr" existe dans l'URL (ex: index.php?id_suppr=0)
    if (isset($_GET['id_suppr'])) {
        $id = $_GET['id_suppr'];
        
        // On retire l'élément du tableau
        unset($personnes[$id]);
        
        
        $personnes = array_values($personnes);
        
        
        file_put_contents($nom_fichier, json_encode($personnes));
        
        
        header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Exercice JSON Complet</title>
</head>
<body>

    <h2>Ajouter une personne</h2>
    <form action="" method="post">
        Nom : <input type="text" name="nom" required>
        Prénom : <input type="text" name="prenom" required>
        Email : <input type="email" name="email" required>
        <button type="submit" name="btn_ajouter">Enregistrer</button>
    </form>

    <hr>

    <h2>Liste des inscrits</h2>
    <table border="1" width="100%">
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        
        <?php 
            
            foreach ($personnes as $cle => $p) {
                echo "<tr>";
                echo "<td>" . $p['nom'] . "</td>";
                echo "<td>" . $p['prenom'] . "</td>";
                echo "<td>" . $p['email'] . "</td>";
                
                echo "<td> <a href='index.php?id_suppr=$cle'>Supprimer</a> </td>";
                echo "</tr>";
            }
        ?>
    </table>

</body>
</html>