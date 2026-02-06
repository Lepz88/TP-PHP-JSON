<?php

$fichier = "projet.json"; 
$toutesLesTaches = [];


if (file_exists($fichier)) {
    $contenu = file_get_contents($fichier);
    $toutesLesTaches = json_decode($contenu, true);
    if ($toutesLesTaches == null) {
        $toutesLesTaches = [];
    }
} else {
    file_put_contents($fichier, '[]');
}


if (isset($_POST['btnAjouter'])) {
    
    $tache = [];
    $tache['id'] = uniqid(); 
    $tache['titre'] = $_POST['titre'];
    $tache['description'] = $_POST['description'];
    $tache['priorite'] = $_POST['priorite'];
    $tache['date_limite'] = $_POST['date_limite'];
    $tache['statut'] = "à faire"; 
    $tache['date_creation'] = date('Y-m-d'); 

    $toutesLesTaches[] = $tache;
    
    $json = json_encode($toutesLesTaches, JSON_PRETTY_PRINT);
    file_put_contents($fichier, $json);

    
    header("Location: miniprojet.php");
    exit();
}



if (isset($_GET['action']) && $_GET['action'] == 'supprimer') {
    $idASupprimer = $_GET['id'];
    
    foreach ($toutesLesTaches as $indice => $tache) {
        if ($tache['id'] == $idASupprimer) {
            array_splice($toutesLesTaches, $indice, 1);
            break; 
        }
    }
    
    $json = json_encode($toutesLesTaches, JSON_PRETTY_PRINT);
    file_put_contents($fichier, $json);
    
    
    header("Location: miniprojet.php");
    exit();
}


if (isset($_GET['action']) && $_GET['action'] == 'changer_statut') {
    $idAModifier = $_GET['id'];
    
    foreach ($toutesLesTaches as $indice => $tache) {
        if ($tache['id'] == $idAModifier) {
            if ($tache['statut'] == 'à faire') {
                $toutesLesTaches[$indice]['statut'] = 'en cours';
            } elseif ($tache['statut'] == 'en cours') {
                $toutesLesTaches[$indice]['statut'] = 'terminée';
            } else {
                $toutesLesTaches[$indice]['statut'] = 'à faire';
            }
            break;
        }
    }
    
    $json = json_encode($toutesLesTaches, JSON_PRETTY_PRINT);
    file_put_contents($fichier, $json);
    
    
    header("Location: miniprojet.php");
    exit();
}


$total = 0;
$terminees = 0;
$retard = 0;
$dateAujourdhui = date('Y-m-d');

foreach ($toutesLesTaches as $tache) {
    $total = $total + 1;

    if ($tache['statut'] == 'terminée') {
        $terminees = $terminees + 1;
    }

    if ($tache['statut'] != 'terminée') {
        if ($tache['date_limite'] < $dateAujourdhui) {
            $retard = $retard + 1;
        }
    }
}

$pourcentage = 0;
if ($total > 0) {
    $pourcentage = round(($terminees / $total) * 100);
}



$tachesAffichees = [];

$recherche = "";
if (isset($_GET['recherche'])) { $recherche = $_GET['recherche']; }

$filtreStatut = "";
if (isset($_GET['filtre_statut'])) { $filtreStatut = $_GET['filtre_statut']; }

$filtrePriorite = "";
if (isset($_GET['filtre_priorite'])) { $filtrePriorite = $_GET['filtre_priorite']; }

foreach ($toutesLesTaches as $tache) {
    $garder = true;

    if ($recherche != "") {
        if (strpos($tache['titre'], $recherche) === false && strpos($tache['description'], $recherche) === false) {
            $garder = false;
        }
    }

    if ($filtreStatut != "") {
        if ($tache['statut'] != $filtreStatut) {
            $garder = false;
        }
    }

    if ($filtrePriorite != "") {
        if ($tache['priorite'] != $filtrePriorite) {
            $garder = false;
        }
    }

    if ($garder == true) {
        $tachesAffichees[] = $tache;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mini Projet PHP</title>
    <link href="css/bootstrap.css" rel="stylesheet">
</head>
<body class="bg-light p-3">

<div class="container">
    <h1 class="text-center text-primary mb-4">Gestionnaire de Tâches</h1>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center bg-white shadow-sm border-primary">
                <div class="card-body">
                    <h6 class="text-muted">Total</h6>
                    <h3><?php echo $total; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-white shadow-sm border-success">
                <div class="card-body">
                    <h6 class="text-muted">Terminées</h6>
                    <h3><?php echo $terminees; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-white shadow-sm border-info">
                <div class="card-body">
                    <h6 class="text-muted">Réussite</h6>
                    <h3><?php echo $pourcentage; ?> %</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-white shadow-sm border-danger">
                <div class="card-body">
                    <h6 class="text-danger">En Retard</h6>
                    <h3><?php echo $retard; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    Nouvelle Tâche
                </div>
                <div class="card-body">
                    <form method="post" action="miniprojet.php">
                        <div class="mb-3">
                            <label>Titre</label>
                            <input type="text" name="titre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Priorité</label>
                            <select name="priorite" class="form-control">
                                <option value="basse">Basse</option>
                                <option value="moyenne">Moyenne</option>
                                <option value="haute">Haute</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Date Limite</label>
                            <input type="date" name="date_limite" class="form-control" required>
                        </div>
                        <button type="submit" name="btnAjouter" class="btn btn-success w-100">Ajouter la tâche</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            
            <div class="card p-3 mb-3 shadow-sm">
                <form method="get" action="miniprojet.php" class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="recherche" class="form-control" placeholder="Rechercher..." value="<?php echo $recherche; ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="filtre_statut" class="form-control">
                            <option value="">-- Statut --</option>
                            <option value="à faire" <?php if($filtreStatut == 'à faire') echo 'selected'; ?>>À faire</option>
                            <option value="en cours" <?php if($filtreStatut == 'en cours') echo 'selected'; ?>>En cours</option>
                            <option value="terminée" <?php if($filtreStatut == 'terminée') echo 'selected'; ?>>Terminée</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="filtre_priorite" class="form-control">
                            <option value="">-- Priorité --</option>
                            <option value="basse" <?php if($filtrePriorite == 'basse') echo 'selected'; ?>>Basse</option>
                            <option value="moyenne" <?php if($filtrePriorite == 'moyenne') echo 'selected'; ?>>Moyenne</option>
                            <option value="haute" <?php if($filtrePriorite == 'haute') echo 'selected'; ?>>Haute</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100">Filtrer</button>
                    </div>
                </form>
            </div>

            <?php 
            if (empty($tachesAffichees)) {
                echo '<div class="alert alert-info">Aucune tâche ne correspond à vos critères.</div>';
            }
            
            foreach ($tachesAffichees as $tache) { 
                $estEnRetard = false;
                if ($tache['statut'] != 'terminée' && $tache['date_limite'] < $dateAujourdhui) {
                    $estEnRetard = true;
                }
            ?>
                <div class="card mb-3 shadow-sm <?php if($estEnRetard) echo 'border-danger border-2'; ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title"><?php echo $tache['titre']; ?></h5>
                            <span>
                                <?php if($tache['priorite'] == 'haute') { ?>
                                    <span class="badge bg-danger">Haute</span>
                                <?php } elseif($tache['priorite'] == 'moyenne') { ?>
                                    <span class="badge bg-warning text-dark">Moyenne</span>
                                <?php } else { ?>
                                    <span class="badge bg-success">Basse</span>
                                <?php } ?>
                            </span>
                        </div>

                        <p class="text-muted small mb-2">
                            Création : <?php echo $tache['date_creation']; ?> | 
                            Limite : <?php echo $tache['date_limite']; ?>
                            <?php if ($estEnRetard) { ?>
                                <span class="text-danger fw-bold ms-2"> EN RETARD</span>
                            <?php } ?>
                        </p>

                        <p class="card-text"><?php echo $tache['description']; ?></p>
                        
                        <hr>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="miniprojet.php?action=changer_statut&id=<?php echo $tache['id']; ?>" class="text-decoration-none fw-bold">
                                Statut : 
                                <?php 
                                    if($tache['statut'] == 'à faire') echo '<span class="badge bg-secondary">À faire</span>';
                                    elseif($tache['statut'] == 'en cours') echo '<span class="badge bg-primary">En cours</span>';
                                    else echo '<span class="badge bg-success">Terminée</span>';
                                ?>
                                <small class="text-muted fw-normal ms-1">(Cliquer pour changer)</small>
                            </a>

                            <a href="miniprojet.php?action=supprimer&id=<?php echo $tache['id']; ?>" 
                               class="btn btn-outline-danger btn-sm"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')">
                                Supprimer
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
</div>

</body>
</html>