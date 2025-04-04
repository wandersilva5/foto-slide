<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Flow - Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <div class="container admin-dashboard">
        <header>
            <h1>Photo Flow - Painel Administrativo</h1>
            <div class="admin-actions">
                <a href="index.php?controller=slide&action=showSlideshow" class="btn" target="_blank">Iniciar Slideshow</a>
                <a href="index.php?controller=admin&action=logout" class="btn btn-danger">Sair</a>
            </div>
        </header>
        
        <main>
            <section class="pending-photos">
                <h2>Fotos Pendentes</h2>
                <?php if(empty($pending_photos)): ?>
                    <p>Não há fotos pendentes para aprovação.</p>
                <?php else: ?>
                    <div class="photos-grid">
                        <?php foreach($pending_photos as $photo): ?>
                            <div class="photo-card" data-id="<?php echo $photo['id']; ?>" data-filename="<?php echo $photo['filename']; ?>">
                                <img src="img/uploads/<?php echo $photo['filename']; ?>" alt="Foto enviada">
                                <div class="photo-info">
                                    <p>Enviada em: <?php echo date('d/m/Y H:i', strtotime($photo['created_at'])); ?></p>
                                    <p>Por: <?php echo $photo['uploaded_by']; ?></p>
                                </div>
                                <div class="photo-actions">
                                    <button class="btn btn-approve">Aprovar</button>
                                    <button class="btn btn-reject">Rejeitar</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
            
            <section class="approved-photos">
                <h2>Fotos Aprovadas</h2>
                <?php if(empty($approved_photos)): ?>
                    <p>Não há fotos aprovadas ainda.</p>
                <?php else: ?>
                    <div class="photos-grid">
                        <?php foreach($approved_photos as $photo): ?>
                            <div class="photo-card">
                                <img src="img/uploads/<?php echo $photo['filename']; ?>" alt="Foto aprovada">
                                <div class="photo-info">
                                    <p>Aprovada em: <?php echo date('d/m/Y H:i', strtotime($photo['created_at'])); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>