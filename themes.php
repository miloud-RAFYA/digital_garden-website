<?php
session_start();
// require_once 'includes/auth.php';
require_once 'includes/header.php';
require_once 'config/database.php';

try {
    $user_Id = $_SESSION['user_id'] ?? null;
    if (!$user_Id) {
        header("Location: login.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enregistrer'])) {

        $nameTheme = $_POST['themeName'] ?? '';
        $themeColor = $_POST['themeColor'] ?? '';
        $themeTags = $_POST['themeTags'] ?? '';

        if (!empty($nameTheme) && !empty($themeColor)) {
            $sql = "INSERT INTO themes (user_id, name, color, tags) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($cnx, $sql);
            mysqli_stmt_bind_param($stmt, "isss", $user_Id, $nameTheme, $themeColor, $themeTags);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success_message'] = "Thème créé avec succès !";
            } else {
                $_SESSION['error_message'] = "Erreur lors de la création : ";
            }
            mysqli_stmt_close($stmt);
            header("Location: themes.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Veuillez remplir tous les champs obligatoires";
            header("Location: themes.php");
            exit();
        }
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
        $theme_id = $_POST["theme_id"] ?? 0;
        if ($theme_id > 0) {
            $sql = "DELETE FROM themes WHERE id = ? AND user_id = ?";
            $stmt = mysqli_prepare($cnx, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $theme_id, $user_Id);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success_message'] = "Thème supprimé avec succès !";
            } else {
                $_SESSION['error_message'] = "Erreur lors de la suppression : " ;
            }
            mysqli_stmt_close($stmt);
            header("Location: themes.php");
            exit();  
        }
    }
    if (isset($_POST["edit"])) {
        $theme_id = $_POST["theme_id"] ?? 0;
        $_SESSION['edit_theme_id'] = $theme_id;
    }

    // RÉCUPÉRATION DES THÈMES
    $query = "SELECT * FROM themes WHERE user_id = ?";
    $stmt = mysqli_prepare($cnx, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $user_Id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = false;
        $_SESSION['error_message'] = "Erreur de connexion à la base de données";
    }

} catch (Exception $e) {
    $_SESSION['error_message'] = "Erreur : " . $e->getMessage();
}
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

    <main class="page">
        <div class="page-header">
            <h2><i class="fas fa-palette"></i> Mes Thèmes</h2>
            <button class="btn-add" id="btn-add">
                <i class="fas fa-plus"></i> Ajouter un thème
            </button>
        </div>

        <!-- Afficher les messages -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION['success_message']) ?>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($_SESSION['error_message']) ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire d'ajout/modification -->
        <div class="form-container" id="themeForm">
            <h3><i class="fas fa-plus-circle"></i> <span id="formTitle">Nouveau Thème</span></h3>
            <form method="POST" action="" id="themeFormElement">
                <!-- Champ caché pour l'ID en mode édition -->
                <input type="hidden" id="themeId" name="theme_id" value="">
                <input type="hidden" id="formAction" name="action" value="create">
                
                <div class="form-group">
                    <label for="themeName">Nom du theme </label>
                    <input type="text" id="themeName" name="themeName"
                        placeholder="Ex: Productivité, Voyage, Développement..." required>
                </div>

                <div class="form-group">
                    <label for="themeColor">Couleur du theme </label>
                    <div class="color-picker-group">
                        <input type="color" id="themeColor" name="themeColor" value="#4CAF50">
                        <div class="color-preview" id="colorPreview">#4CAF50</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="themeTags">Tags (optionnel)</label>
                    <input type="text" id="themeTags" name="themeTags" placeholder="Séparés par des virgules">
                    <small class="form-text">Ex: travail,projet,important</small>
                </div>

                <div class="form-actions">
                    <button type="submit" name="enregistrer" class="btn-save" id="submitBtn">
                        <i class="fas fa-save"></i> <span id="submitText">Créer</span>
                    </button>
                    <button type="button" class="btn-cancel" id="btn-cancel">
                        Annuler
                    </button>
                </div>
            </form>
        </div>

        <!-- Liste des thèmes -->
        <div class="theme-list">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($theme = mysqli_fetch_assoc($result)): ?>
                    <div class="theme-card" style="--theme-color: <?= htmlspecialchars($theme['color']) ?>"
                         data-id="<?= $theme['id'] ?>"
                         data-name="<?= htmlspecialchars($theme['name']) ?>"
                         data-color="<?= htmlspecialchars($theme['color']) ?>"
                         data-tags="<?= htmlspecialchars($theme['tags']) ?>">
                        <div class="theme-header">
                            <div class="theme-color" style="background: <?= htmlspecialchars($theme['color']) ?>"></div>
                            <div class="theme-info">
                                <h3><?= htmlspecialchars($theme['name']) ?></h3>
                                <div class="theme-meta">
                                    <i class="fas fa-sticky-note"></i>
                                    <span>
                                        <?php
                                        $count_query = "SELECT COUNT(*) FROM notes WHERE theme_id = ? AND user_id = ?";
                                        $count_stmt = mysqli_prepare($cnx, $count_query);
                                        mysqli_stmt_bind_param($count_stmt, "ii", $theme['id'], $user_Id);
                                        mysqli_stmt_execute($count_stmt);
                                        mysqli_stmt_bind_result($count_stmt, $note_count);
                                        mysqli_stmt_fetch($count_stmt);
                                        mysqli_stmt_close($count_stmt);
                                        echo $note_count . " note" . ($note_count > 1 ? 's' : '');
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($theme['tags'])): ?>
                            <div class="theme-tags">
                                <?php
                                $tags = explode(',', $theme['tags']);
                                foreach ($tags as $tag):
                                    if (trim($tag)): ?>
                                        <span class="tag"><?= htmlspecialchars(trim($tag)) ?></span>
                                    <?php endif;
                                endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="theme-actions">
                            <!-- Bouton Modifier -->
                            <button type="button" class="btn btn-primary btn-edit" 
                                    data-id="<?= $theme['id'] ?>"
                                    data-name="<?= htmlspecialchars($theme['name']) ?>"
                                    data-color="<?= htmlspecialchars($theme['color']) ?>"
                                    data-tags="<?= htmlspecialchars($theme['tags']) ?>">
                                <i class="fas fa-edit"></i> Modifier
                            </button>

                            <!-- Formulaire de suppression -->
                            <form method="POST" action="" class="form-inline"
                                onsubmit="return confirm('Voulez-vous vraiment supprimer ce thème ?');">
                                <input type="hidden" name="theme_id" value="<?= $theme['id'] ?>">
                                <button type="submit" name="delete" class="btn btn-secondary">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-palette"></i>
                    <p>Aucun thème pour le moment</p>
                    <p style="font-size: 14px; color: #999;">Cliquez sur "Ajouter un thème" pour commencer</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    

    <?php
    if (isset($stmt)) {
        mysqli_stmt_close($stmt);
    }
    if (isset($cnx)) {
        mysqli_close($cnx);
    }
    ?>
</body>
</html>