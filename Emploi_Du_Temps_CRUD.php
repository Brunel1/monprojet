<?php
$host = 'localhost';
$user = 'root'; // Remplacez par votre utilisateur
$pass = ''; // Remplacez par votre mot de passe
$db = 'emploi_du_temps';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ajouter un emploi du temps
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'ajouter') {
    $jour = $_POST['jour'];
    $heure = $_POST['heure'];
    $matiere = $_POST['matiere'];

    $sql = "INSERT INTO horaire (jour, heure, matiere) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $jour, $heure, $matiere);
    $stmt->execute();
}

// Supprimer un emploi du temps
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'supprimer') {
    $id = $_POST['id'];

    $sql = "DELETE FROM horaire WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Récupérer les horaire
$sql = "SELECT * FROM horaire ORDER BY FIELD('jour', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'), heure";
$result = $conn->query($sql);
$horaire = [];
while ($row = $result->fetch_assoc()) {
    $horaire[$row['jour']][$row['heure']] = $row['matiere'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du Temps</title>
    <style>
        body {
            align-items: center;
            /*display: flex;*/
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            height: 100vh;
            margin: 0;
            justify-content: center; 
        }
        

        h1 {
            color: #333;
            animation: typing 3s steps(80) infinite alternate-reverse;
            /*font-family:Arial, Helvetica, sans-serif;*/
            margin: 0 auto;
            overflow: hidden;
            text-wrap: nowrap;
            width: 16ch;
            text-align: center;
        }

        @keyframes typing {
            from {width: 0ch;}
            } 

        h3 {
            text-align: center;
            font-family: cursive;
        }

        .hideden-header {
            /*display: none;*/
            background-color: white;
            color: white;
         }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }

        .container {
            display: flexbox;
            justify-items: center;
        }

        .top-right {
                position: fixed;
                bottom: 0;
                /*height: 70px;*/
                /*left: 95%;*/
                /*margin: 60px;*/
                padding: 0;
                right: 0;
                text-orientation: vertical-rl;
                /*transform: rotate(-89deg);*/
                transform-origin: right;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Emploi du Temps</h1>
        <!-- Formulaire d'ajout -->
        <h3>Ajouter un Emploi du Temps</h3>
        <form method="POST">
            <input type="hidden" name="action" value="ajouter">
            <label>Jour:</label>
            <select name="jour" required>
                <option value="Lundi">Lundi</option>
                <option value="Mardi">Mardi</option>
                <option value="Mercredi">Mercredi</option>
                <option value="Jeudi">Jeudi</option>
                <option value="Vendredi">Vendredi</option>
                <option value="Samedi">Samedi</option>
                <option value="Dimanche">Dimanche</option>
            </select>
            <label>Heure:</label>
            <input type="text" name="heure" required placeholder="HH:MM - HH:MM" style="width: 110px;">
            <label>Matière:</label>
            <input type="text" name="matiere" required>
            <input type="submit" value="Ajouter">
        </form>
    </div>

    <!-- Affichage des emplois du temps -->
        <h3>Liste des Emplois du Temps</h3>
        <table>
            <thead>
                <tr>
                    <th>Heure</th>
                    <th>Lundi</th>
                    <th>Mardi</th>
                    <th>Mercredi</th>
                    <th>Jeudi</th>
                    <th>Vendredi</th>
                    <th>Samedi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $heures = ["06:00 - 07:00", "07:00 - 08:00","08:00 - 09:00", "09:00 - 10:00", "10:00 - 11:00", "11:00 - 12:00"];
                    foreach ($heures as $heure) {
                    echo "<tr>";
                    echo "<td>$heure</td>";
                    
                    foreach (["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"] as $jour) {
                    echo "<td>" . (isset($horaire[$jour][$heure]) ? htmlspecialchars ($horaire[$jour][$heure]) : '') . "</td>";
                    }
                    echo "</tr>";
                    }
                ?>
            </tbody>
        </table>

        <h3>APRES-MIDI</h3>
        <table>
            <thead>
                <tr class="hideden-header">
                    <th style=" background-color: #333; color: #4CAF50;">Heure</th>
                    <th style=" background-color: #333; color: #333;">Lundi</th>
                    <th style=" background-color: #333; color:  #333;">Mardi</th>
                    <th style=" background-color: #333; color:  #333;">Mercredi</th>
                    <th style=" background-color: #333; color:  #333;">Jeudi</th>
                    <th style=" background-color: #333; color:  #333;">Vendredi</th>
                    <th style=" background-color: #333; color:  #333;">Samedi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $heures = ["14:00 - 15:00","15:00 - 16:00", "16:00 - 17:00", "17:00 - 18:00", "18:00 - 19:00"];
                    foreach ($heures as $heure) {
                    echo "<tr>";
                    echo "<td>$heure</td>";
            
                    foreach (["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"] as $jour) {
                    echo "<td>" . (isset($horaire[$jour][$heure]) ? htmlspecialchars ($horaire[$jour][$heure]) : '') . "</td>";
                    }
                    echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
        <div class="container">
            <!-- Formulaire de suppression -->
            <h3>Supprimer un Emploi du Temps</h3>
            <form method="POST">
                <input type="hidden" name="action" value="supprimer">
                <label>ID (à supprimer):</label>
                <input type="text" name="id" required>
                <input type="submit" value="Supprimer">
            </form>
        </div>
        <p class="top-right">WEBHerveBrunelLICENCE&copy;<span id="currentYear"></span></p>
        <script>
        document.getElementById("currentYear").textContent = new Date().getFullYear();
    </script>
</body>
</html>

<?php
$conn->close();
?>

