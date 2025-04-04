<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Flow - Slideshow</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #000;
        }
        .slideshow-container {
            width: 100vw;
            height: 100vh;
            position: relative;
        }
        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .slide.active {
            opacity: 1;
        }
        .slide img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .no-photos {
            color: white;
            text-align: center;
            font-size: 2rem;
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="slideshow-container" id="slideshow">
        <div class="no-photos">Carregando fotos...</div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slideshowContainer = document.getElementById('slideshow');
            let photos = [];
            let currentSlide = 0;
            
            // Fetch approved photos
            async function fetchPhotos() {
                try {
                    const response = await fetch('index.php?controller=slide&action=getApprovedPhotos');
                    if(!response.ok) {
                        throw new Error('Failed to fetch photos');
                    }
                    
                    photos = await response.json();
                    
                    if(photos.length === 0) {
                        slideshowContainer.innerHTML = '<div class="no-photos">Não há fotos aprovadas para exibição ainda.</div>';
                        // Check again after 30 seconds
                        setTimeout(fetchPhotos, 30000);
                        return;
                    }
                    
                    // Create slides
                    slideshowContainer.innerHTML = '';
                    photos.forEach((photo, index) => {
                        const slide = document.createElement('div');
                        slide.className = index === 0 ? 'slide active' : 'slide';
                        
                        const img = document.createElement('img');
                        img.src = 'img/uploads/' + photo.filename;
                        img.alt = 'Foto da festa';
                        
                        slide.appendChild(img);
                        slideshowContainer.appendChild(slide);
                    });
                    
                    // Start slideshow
                    startSlideshow();
                    
                    // Check for new photos every 2 minutes
                    setTimeout(fetchPhotos, 120000);
                } catch (error) {
                    console.error('Error fetching photos:', error);
                    slideshowContainer.innerHTML = '<div class="no-photos">Erro ao carregar fotos. Tentando novamente em 30 segundos...</div>';
                    // Try again after 30 seconds
                    setTimeout(fetchPhotos, 30000);
                }
            }
            
            // Start the slideshow rotation
            function startSlideshow() {
                setInterval(() => {
                    // Hide current slide
                    const slides = document.querySelectorAll('.slide');
                    slides[currentSlide].classList.remove('active');
                    
                    // Move to next slide
                    currentSlide = (currentSlide + 1) % slides.length;
                    
                    // Show next slide
                    slides[currentSlide].classList.add('active');
                }, 5000); // Change slide every 5 seconds
            }
            
            // Initial load
            fetchPhotos();
        });
    </script>
</body>
</html>