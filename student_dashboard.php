<?php
session_start();
require_once 'Database.php';
require_once 'Request.php';

if ($_SESSION['role'] !== 'student') {
  header('Location: login.html');
  exit();
}

$db = new Database();
$requestHandler = new Request($db);

// Fetch the student's requests
$studentId = $_SESSION['user_id'];
$requests = $requestHandler->getRequests($studentId);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de bord étudiant</title>
  <!-- Inclure le CSS Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container my-5">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="text-primary">Tableau de bord étudiant</h1>
      <form action="logout.php" method="POST">
        <button type="submit" class="btn btn-danger">Se déconnecter</button>
      </form>
    </div>

    <!-- Créer une nouvelle demande -->
    <div class="card shadow mb-4">
      <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Créer une nouvelle demande</h4>
      </div>
      <div class="card-body">
        <form action="create_request.php" method="POST">
          <input type="hidden" name="student_id" value="<?= $studentId ?>">
          <input type="hidden" name="student_name" value="<?= htmlspecialchars($_SESSION['username']) ?>">

          <div class="mb-3">
            <label for="student-choice" class="form-label">Type de demande</label>
            <select name="student-choice" id="student-choice" class="form-select">
              <option value="Certificat de scolarité">Certificat de scolarité</option>
              <option value="Attestation de bonne conduite">Attestation de bonne conduite</option>
              <option value="Relevé de notes">Relevé de notes</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">Soumettre la demande</button>
        </form>
      </div>
    </div>

    <!-- Tableau de vos demandes -->
    <div class="card shadow">
      <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Vos demandes</h4>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-hover">
          <thead class="table-primary">
            <tr>
              <th>ID</th>
              <th>Description</th>
              <th>Statut</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $requests->fetch_assoc()): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['file_description']) ?></td>
                <td>
                  <span class="badge 
                    <?= $row['status'] === 'Pending' ? 'bg-warning' :
                      ($row['status'] === 'Approved' ? 'bg-success' : 'bg-danger') ?>">
                    <?= $row['status'] ?>
                  </span>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Inclure le bundle Bootstrap avec Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
