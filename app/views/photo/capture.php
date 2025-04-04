<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Flow - Capturar Foto</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Photo Flow</h1>
        <h2>Compartilhe momentos da sua comemoração!</h2>
        
        <div class="camera-container">
            <video id="camera" autoplay playsinline></video>
            <canvas id="canvas" style="display:none;"></canvas>
            <div class="camera-controls">
                <button id="capture-btn" class="btn btn-primary">Tirar Foto</button>
                <button id="retake-btn" class="btn" style="display:none;">Nova Foto</button>
                <button id="upload-btn" class="btn btn-success" style="display:none;">Enviar</button>
            </div>
        </div>
        
        <form id="upload-form" method="POST" action="index.php?controller=photo&action=uploadPhoto" enctype="multipart/form-data" style="display:none;">
            <input type="file" id="photo-input" name="photo" accept="image/*">
        </form>
        
        <div class="note">
            <p>Todas as fotos serão revisadas antes de aparecerem na apresentação.</p>
        </div>
    </div>

    <script>
        // Camera functionality
        const video = document.getElementById('camera');
        const canvas = document.getElementById('canvas');
        const captureBtn = document.getElementById('capture-btn');
        const retakeBtn = document.getElementById('retake-btn');
        const uploadBtn = document.getElementById('upload-btn');
        const photoInput = document.getElementById('photo-input');
        const uploadForm = document.getElementById('upload-form');
        
        let stream;
        let photoTaken = false;
        
        // Initialize camera
        async function initCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: 'environment' }, 
                    audio: false 
                });
                video.srcObject = stream;
            } catch (err) {
                console.error('Erro ao acessar a câmera: ', err);
                alert('Não foi possível acessar sua câmera. Verifique as permissões do navegador.');
            }
        }
        
        // Start camera on page load
        window.addEventListener('load', initCamera);
        
        // Capture photo
        captureBtn.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Show canvas instead of video
            video.style.display = 'none';
            canvas.style.display = 'block';
            
            // Toggle buttons
            captureBtn.style.display = 'none';
            retakeBtn.style.display = 'inline-block';
            uploadBtn.style.display = 'inline-block';
            
            photoTaken = true;
        });
        
        // Retake photo
        retakeBtn.addEventListener('click', () => {
            // Show video again
            video.style.display = 'block';
            canvas.style.display = 'none';
            
            // Toggle buttons
            captureBtn.style.display = 'inline-block';
            retakeBtn.style.display = 'none';
            uploadBtn.style.display = 'none';
            
            photoTaken = false;
        });
        
        // Upload photo
        uploadBtn.addEventListener('click', () => {
            if(!photoTaken) return;
            
            // Convert canvas to file
            canvas.toBlob(blob => {
                const file = new File([blob], 'photo.jpg', { type: 'image/jpeg' });
                
                // Create a FileList-like object
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                photoInput.files = dataTransfer.files;
                
                // Submit form
                uploadForm.submit();
            }, 'image/jpeg', 0.9);
        });
    </script>
</body>
</html>