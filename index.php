<?php 
$pageTitle = 'Aserradería Pequinez | Maderas de Calidad';
$basePath = './'; 
$additionalCSS = ['public/css/paginaprincipal.css'];//AQUI LLAMO AL CSS PARA MI PAGINA WEB, EN .PHP  ES DIFERENTE EL COMANDO

include('public/layaout/header.php'); 
?>



<main class="home-page">
 >
    <section class="hero-section">
        <div class="container-fluid">
            <div class="row align-items-center min-vh-80">
                <div class="col-lg-6 col-md-12 hero-content-wrapper">
                    <div class="hero-content">
                    </div>
                </div>
                        
            </div>
        </div>
                
        
                <div class="col-lg-3 col-md-9 hero-image-wrapper">
                    <div class="hero-image-container">
                        <div class="decoration-circle circle-1"></div>
                           <div class="decoration-circle circle-2"></div>
                             <div class="decoration-leaf leaf-1">
                                 <ion-icon name="leaf"></ion-icon>
                            </div>
                        <div class="decoration-leaf leaf-2">
                            <ion-icon name="leaf"></ion-icon>
                    </div>
                        
                 <img src="public/img/icons/Leñador-removebg-preview.png" 
                            alt="Aserradería Pequinez - Maderas de Calidad" 
                             class="hero-main-image" />
                        
                        <div class="floating-card">
                            <div class="floating-card-content">
                                <div class="floating-icon">
                                    <ion-icon name="tree"></ion-icon>
                                         </div>
                                            <div class="floating-text">
                                                  <strong>100+</strong>
                                                 <small>Proyectos Completados</small>
                                            </div>
                                        </div>
                                 </div>
                             </div>
                      </div>
                 </div>
             </div>
    </section>


    
</main>

<?php 
include('public/layaout/footer.php'); 
?>
