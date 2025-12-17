<?php
session_start();
// require_once 'includes/auth.php';
require_once 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Thèmes | Digital Garden</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="public/css/style.css">
</head>

<body>

  <?php require_once 'includes/header.php'; ?>

  <main class="page themes-page">
    <div class="page-header">
      <h2><i class="fas fa-palette"></i> Mes Thèmes</h2>
      <button class="btn-add" onclick="toggleForm()">
        <i class="fas fa-plus"></i> Ajouter un thème
      </button>
    </div>

    <!-- Formulaire d'ajout/modification -->
    <div class="form-container" id="themeForm">
      <h3><i class="fas fa-plus-circle"></i> Nouveau Thème</h3>
      <form>
        <div class="form-group">
          <label for="themeName">Nom du thème</label>
          <input type="text" id="themeName" placeholder="Ex: Productivité, Voyage, Développement...">
        </div>

        <div class="form-group">
          <label>Couleur du thème</label>
          <div class="color-picker-group">
            <input type="color" id="themeColor" value="#4CAF50" onchange="updateColorPreview()">
            <div class="color-preview" id="colorPreview">#4CAF50</div>
          </div>
        </div>

        <div class="form-group">
          <label for="themeTags">Tags (optionnel)</label>
          <input type="text" id="themeTags" placeholder="Séparés par des virgules">
        </div>

        <div class="form-actions">
          <button type="button" class="btn-save">
            <i class="fas fa-save"></i> Enregistrer
          </button>
          <button type="button" class="btn-cancel" onclick="toggleForm()">
            Annuler
          </button>
        </div>
      </form>
    </div>

    <!-- Liste des thèmes -->
    <div class="theme-list">

      <div class="theme-card" style="--theme-color: #4CAF50">
        <div class="theme-header">
          <div class="theme-color" style="background: #4CAF50"></div>
          <div class="theme-info">
            <h3>Productivité</h3>
            <div class="theme-meta">
              <i class="fas fa-sticky-note"></i>
              <span>5 notes</span>
            </div>
          </div>
        </div>
        <div class="theme-actions">
          <button class="btn btn-primary">
            <i class="fas fa-edit"></i> Modifier
          </button>
          <button class="btn btn-secondary">
            <i class="fas fa-trash"></i> Supprimer
          </button>
        </div>
      </div>

      <div class="theme-card" style="--theme-color: #FF9800">
        <div class="theme-header">
          <div class="theme-color" style="background: #FF9800"></div>
          <div class="theme-info">
            <h3>Voyage</h3>
            <div class="theme-meta">
              <i class="fas fa-sticky-note"></i>
              <span>3 notes</span>
            </div>
          </div>
        </div>
        <div class="theme-actions">
          <button class="btn btn-primary">
            <i class="fas fa-edit"></i> Modifier
          </button>
          <button class="btn btn-secondary">
            <i class="fas fa-trash"></i> Supprimer
          </button>
        </div>
      </div>

      <div class="theme-card" style="--theme-color: #9C27B0">
        <div class="theme-header">
          <div class="theme-color" style="background: #9C27B0"></div>
          <div class="theme-info">
            <h3>Développement</h3>
            <div class="theme-meta">
              <i class="fas fa-sticky-note"></i>
              <span>8 notes</span>
            </div>
          </div>
        </div>
        <div class="theme-actions">
          <button class="btn btn-primary">
            <i class="fas fa-edit"></i> Modifier
          </button>
          <button class="btn btn-secondary">
            <i class="fas fa-trash"></i> Supprimer
          </button>
        </div>
      </div>

      <div class="theme-card" style="--theme-color: #2196F3">
        <div class="theme-header">
          <div class="theme-color" style="background: #2196F3"></div>
          <div class="theme-info">
            <h3>Études</h3>
            <div class="theme-meta">
              <i class="fas fa-sticky-note"></i>
              <span>12 notes</span>
            </div>
          </div>
        </div>
        <div class="theme-actions">
          <button class="btn btn-primary">
            <i class="fas fa-edit"></i> Modifier
          </button>
          <button class="btn btn-secondary">
            <i class="fas fa-trash"></i> Supprimer
          </button>
        </div>
      </div>

    </div>
    <div class="loading-indicator" id="loadingIndicator">
      <i class="fas fa-spinner"></i>
      <p>Chargement des thèmes...</p>
    </div>
    <div class="instructions">
      <h4><i class="fas fa-lightbulb"></i> Comment utiliser les thèmes ?</h4>
      <p>Créez des thèmes pour organiser vos notes par catégorie. Chaque thème peut avoir une couleur et des tags
        associés. Cliquez sur un thème pour voir toutes les notes qui lui sont associées.</p>
    </div>

    <!-- Notification (à ajouter avant la fermeture de body) -->
    <div class="notification" id="notification">
      <i class="fas fa-check-circle"></i>
      <span>Thème enregistré avec succès !</span>
    </div>
    <!-- État vide (à afficher si aucun thème) -->
    <!-- 
    <div class="empty-state">
      <i class="fas fa-palette"></i>
      <p>Aucun thème pour le moment</p>
      <p style="font-size: 14px; color: #999;">Cliquez sur "Ajouter un thème" pour commencer</p>
    </div>
    -->

  </main>

  <script>
    function toggleForm() {
      const form = document.getElementById('themeForm');
      form.classList.toggle('active');

      // Scroll vers le formulaire quand il s'ouvre
      if (form.classList.contains('active')) {
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }

    function updateColorPreview() {
      const colorInput = document.getElementById('themeColor');
      const colorPreview = document.getElementById('colorPreview');
      colorPreview.textContent = colorInput.value.toUpperCase();
      colorPreview.style.background = colorInput.value + '20';
      colorPreview.style.border = '2px solid ' + colorInput.value;
    }

    // Initialiser l'aperçu de couleur au chargement
    document.addEventListener('DOMContentLoaded', function () {
      updateColorPreview();
    });
  </script>

</body>

</html>