<?php
require_once "../includes/authentication.php";
$page_title = "About Us";
include_once "includes/header.php";
include_once "includes/navbar.php";
?>

<!-- Hero Section -->
<section class="about-hero">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1>About CCS</h1>
                <p class="lead">Shaping the Future of Computing Education</p>
            </div>
            <div class="col-lg-6">
                <img src="../img/AUTHENTICATION.jpg" alt="CCS Building" class="hero-image">
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision Section -->
<section class="mission-vision">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="content-card mission">
                    <div class="icon">
                        <i class="lni lni-target"></i>
                    </div>
                    <h2>Our Mission</h2>
                    <p>To provide quality education in computing sciences, fostering innovation, research, and professional excellence while promoting social responsibility and ethical practices in the field of technology.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="content-card vision">
                    <div class="icon">
                        <i class="lni lni-eye"></i>
                    </div>
                    <h2>Our Vision</h2>
                    <p>To be a leading institution in computing education, recognized globally for academic excellence, innovative research, and producing industry-ready professionals who contribute to technological advancement and societal development.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Programs Section -->
<section class="programs">
    <div class="container">
        <h2 class="section-title">Our Programs</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="program-card">
                    <img src="../img/program1.jpg" alt="BSCS" class="program-image">
                    <div class="program-content">
                        <h3>BS Computer Science</h3>
                        <p>Focus on theoretical foundations of computing and modern software development.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="program-card">
                    <img src="../img/program2.jpg" alt="BSIT" class="program-image">
                    <div class="program-content">
                        <h3>BS Information Technology</h3>
                        <p>Emphasis on practical applications of technology in business environments.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="program-card">
                    <img src="../img/program3.jpg" alt="BSIS" class="program-image">
                    <div class="program-content">
                        <h3>BS Information Systems</h3>
                        <p>Integration of technology solutions with business processes.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="contact">
    <div class="container">
        <div class="contact-info">
            <h2>Get in Touch</h2>
            <div class="info-items">
                <div class="info-item">
                    <i class="lni lni-map-marker"></i>
                    <p>123 University Avenue, Manila, Philippines</p>
                </div>
                <div class="info-item">
                    <i class="lni lni-envelope"></i>
                    <p>ccs@university.edu.ph</p>
                </div>
                <div class="info-item">
                    <i class="lni lni-phone"></i>
                    <p>+63 2 1234 5678</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Hero Section */
.about-hero {
    position: relative;
    background-color: #004225;
    padding: 100px 0;
    color: white;
    overflow: hidden;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0,66,37,0.95) 0%, rgba(0,104,56,0.85) 100%);
}

.hero-image {
    width: 100%;
    max-width: 500px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

/* Mission & Vision Section */
.mission-vision {
    padding: 80px 0;
    background-color: #f8f9fa;
}

.content-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    transition: transform 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.content-card:hover {
    transform: translateY(-5px);
}

.content-card .icon {
    font-size: 2.5rem;
    color: #004225;
    margin-bottom: 1rem;
}

/* Programs Section */
.programs {
    padding: 80px 0;
}

.section-title {
    text-align: center;
    margin-bottom: 3rem;
    color: #004225;
}

.program-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    margin-bottom: 2rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.program-card:hover {
    transform: translateY(-5px);
}

.program-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.program-content {
    padding: 1.5rem;
}

/* Contact Section */
.contact {
    padding: 80px 0;
    background-color: #f8f9fa;
}

.contact-info {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.contact-info h2 {
    text-align: center;
    margin-bottom: 2rem;
    color: #004225;
}

.info-items {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.info-item i {
    font-size: 1.5rem;
    color: #004225;
}

/* Responsive Design */
@media (max-width: 768px) {
    .about-hero {
        text-align: center;
    }
    
    .hero-image {
        margin-top: 2rem;
    }
    
    .info-item {
        flex-direction: column;
        text-align: center;
    }
    
    .info-item p {
        margin: 0;
    }
}
</style>

<?php include_once "includes/footer.php"; ?>
