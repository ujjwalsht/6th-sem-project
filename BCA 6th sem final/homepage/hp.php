<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sangam Auto Workshop - Premium Auto Services</title>
  <link rel="stylesheet" href="css_home.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body>

  <!-- Header -->
  <header>
    <div class="header-container">
      <div class="logo-container">
        <a href="hp.php">
          <img class="logo" src="../IMG/logo.png" alt="Sangam Auto Workshop Logo">
        </a>
        <div class="brand">
          <h1>Sangam Auto Workshop</h1>
          <p>Your trusted auto service partner</p>
        </div>
      </div>
      <div class="button">
  <?php if (!isset($_SESSION['user_id'])): ?>
    <?php if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'): ?>
      <a href="../login/login.php" class="btn login">Login</a>
      <a href="../register/signup.php" class="btn register">Register</a>
    <?php endif; ?>
  <?php else: ?>
    <a href="../logout.php" class="logout">Logout</a>
    <?php if ($_SESSION['role'] === 'admin'): ?>
      <a href="../admindashboard/admin_dashboard.php" class="logout">Back to Admin Dashboard</a>
    <?php else: ?>
      <a href="../userdashboard/user_dashboard.php" class="logout">Back to User Dashboard</a>
    <?php endif; ?>
  <?php endif; ?>
</div>

    </div>
  </header>

  <!-- Navbar -->
  <nav class="navbar">
    <ul class="nav-menu">
      <li><a href="#home">Home</a></li>
      <li><a href="#services">Services</a></li>
      <li><a href="../contact.php">Contact</a></li>
      <li><a href="#location">Location</a></li>
    </ul>
  </nav>

  <!-- Hero Section -->
  <section id="home" class="hero">
    <div class="hero-content">
      <h2>Revamp Your Vehicle Today</h2>
      <p class="tagline">Expert mechanics, advanced diagnostics. Drive smooth with our professional car care service.</p>
      <p>Our expert mechanics ensure that your car receives the highest quality service with attention to every detail.</p>
      <p>Whether it's engine problems, brake repairs, or regular maintenance, we handle it all with precision and care.</p>
      <p>Our goal is to keep your vehicle running smoothly, safely, and efficiently on every drive.</p>
      <p>With professional service and a customer-first approach, we make car care easy and stress-free.</p>
      <a href="#services" class="btn cta-btn">Explore Services</a>
    </div>
  </section>

  <!-- Services Section -->
  <section id="services" class="services-section">
    <div class="section-header">
      <h2>Our Services</h2>
      <p>Quality auto services tailored to your needs</p>
    </div>
    
    <div class="services-categories">
      <div class="category-card" onclick="showServiceDetails('brake')">
        <div class="category-icon"><i class="fas fa-car-crash"></i></div>
        <h3>Brake System</h3>
      </div>
      <div class="category-card" onclick="showServiceDetails('engine')">
        <div class="category-icon"><i class="fas fa-gear"></i></div>
        <h3>Engine Services</h3>
      </div>
      <div class="category-card" onclick="showServiceDetails('tire')">
        <div class="category-icon"><i class="fas fa-life-ring"></i></div>
        <h3>Tire & Wheel</h3>
      </div>
      <div class="category-card" onclick="showServiceDetails('electrical')">
        <div class="category-icon"><i class="fas fa-bolt"></i></div>
        <h3>Electrical System</h3>
      </div>
      <div class="category-card" onclick="showServiceDetails('cooling')">
        <div class="category-icon"><i class="fas fa-fan"></i></div>
        <h3>Cooling & AC</h3>
      </div>
      <div class="category-card" onclick="showServiceDetails('transmission')">
        <div class="category-icon"><i class="fas fa-cogs"></i></div>
        <h3>Transmission</h3>
      </div>
      <div class="category-card" onclick="showServiceDetails('maintenance')">
        <div class="category-icon"><i class="fas fa-tools"></i></div>
        <h3>Maintenance</h3>
      </div>
      <div class="category-card" onclick="showServiceDetails('suspension')">
        <div class="category-icon"><i class="fas fa-car-side"></i></div>
        <h3>Suspension and Exhaust</h3>
      </div>
    </div>

    <!-- Service Details -->
    <div id="service-details" class="service-details-container" style="display:none;">
      <button class="back-btn" onclick="hideServiceDetails()">
        <i class="fas fa-arrow-left"></i> Back to Categories
      </button>
      <div id="service-details-content" class="service-details-content"></div>
    </div>
  </section>

  <!-- Location -->
  <section id="location" class="services-section">
    <div class="location-map">
      <h2 align="center">Location</h2>
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.4567890123456!2d85.3468257!3d27.7323848!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb19aa765702fd%3A0x931c08830fb1544d!2sSangam%20auto!5e0!3m2!1sen!2snp!4v1234567890123!5m2!1sen!2snp" 
        width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade" title="Sangam Auto Workshop Location">
      </iframe>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-logo">
        <img src="../IMG/logo.png" alt="Sangam Auto Workshop Logo">
        <p>Your trusted auto service partner in Kathmandu</p>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 Sangam Auto Workshop. All Rights Reserved.</p>
    </div>
  </footer>

  <!-- JavaScript -->
  <script>
    const serviceData = {
      brake: {
        title: "Brake System Services",
        services: [
          "Brake Failure Diagnosis and Repair",
          "Brake Pad Replacement",
          "Complete Brake System Check",
          "Brake Fluid Leak Repair"
        ],
        image: "../IMG/hpimages/brake-service.jpg"
      },
      engine: {
        title: "Engine and Performance Services",
        services: [
          "Complete Engine Tune-up",
          "Spark Plug Replacement",
          "Timing Belt Replacement",
          "Alternator Repair",
          "Starter Motor Repair"
        ],
        image: "../IMG/hpimages/engine-service.jpg"
      },
      tire: {
        title: "Tire and Wheel Services",
        services: [
          "Flat Tire Repair/Replacement",
          "Professional Tire Rotation",
          "Precision Wheel Alignment",
          "Wheel Balancing",
          "Tire Pressure Check and Adjustment"
        ],
        image: "../IMG/hpimages/tire-service.jpg"
      },
      electrical: {
        title: "Electrical System Services",
        services: [
          "Battery Replacement",
          "Dashboard Warning Light Diagnosis",
          "Headlight/Taillight Replacement",
          "Complete Electrical System Diagnostic"
        ],
        image: "../IMG/hpimages/electrical-service.jpg"
      },
      cooling: {
        title: "Cooling and AC Services",
        services: [
          "AC Not Cooling Diagnosis",
          "Complete Coolant Flush",
          "Radiator Repair and Maintenance"
        ],
        image: "../IMG/hpimages/cooling-service.jpg"
      },
      transmission: {
        title: "Transmission Services",
        services: [
          "Transmission Service",
          "Clutch Repair",
          "Power Steering Repair"
        ],
        image: "../IMG/hpimages/transmission-service.jpg"
      },
      maintenance: {
        title: "General Maintenance",
        services: [
          "Oil Change",
          "Air Filter Replacement",
          "Fuel System Cleaning",
                 ],
        image: "../IMG/hpimages/maintenance-service.jpg"
      },
      suspension: {
        title: "Suspension and Exhaust Services",
        services: [
          "Suspension Repair",
          "Exhaust System Repair"
        ],
        image: "../IMG/hpimages/suspension-service.jpg"
      }
    };

    function showServiceDetails(category) {
      const detailsContainer = document.getElementById('service-details');
      const contentContainer = document.getElementById('service-details-content');
      const categoryData = serviceData[category];

      let htmlContent = `
        <h3>${categoryData.title}</h3>
        <div class="service-detail-content">
          <img src="${categoryData.image}" alt="${categoryData.title}">
          <ul class="service-list">
            ${categoryData.services.map(service => `<li>${service}</li>`).join('')}
          </ul>
        </div>
        <a href="../userdashboard/user_dashboard.php" class="btn book-btn">Book Appointment</a>
      `;

      contentContainer.innerHTML = htmlContent;
      detailsContainer.style.display = 'block';
      detailsContainer.scrollIntoView({ behavior: 'smooth' });
    }

    function hideServiceDetails() {
      document.getElementById('service-details').style.display = 'none';
    }
  </script>

</body>
</html>
