<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Sangam Auto</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
        }

        /* Header Styles */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: #2c3e50;
            color: white;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo {
            height: 150px;
            margin-right: 15px;
        }

        .brand h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        .brand p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        /* Button Styles */
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login {
            background-color: #e74c3c;
            color: white;
            border: 1px solid #e74c3c;
            margin-right: 10px;
        }

        .login:hover {
            background-color: #c0392b;

        }

        .register {
            background-color: #e74c3c;
            color: white;
            border: 1px solid #e74c3c;
        }

        .register:hover {
            background-color: #c0392b;
        }

        /* Navigation Styles */
        .navbar {
            background-color: #34495e;
        }

        .nav-menu {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .nav-menu li {
            padding: 1rem;
        }

        .nav-menu li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }

        .nav-menu li:hover {
            background-color: #2c3e50;
        }

        /* Main Content Styles */
        .main-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .section-header h2 {
            font-size: 2rem;
            color: #2c3e50;
        }

        .section-header p {
            color: #7f8c8d;
        }

        /* Contact Section */
        .contact-container {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        /* Owner Section */
        .owner-container {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .owner-details {
            flex: 1;
        }

        .owner-photo {
            max-height: 200px;
            max-width: 200px;
            border-radius: 50%;
            border: 3px solid #e74c3c;
            object-fit: cover;
        }

        /* Workers Grid */
        .workers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .contact-card {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .contact-card h3 {
            color: #2c3e50;
            margin-top: 0;
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 0.5rem;
        }

        .contact-card p {
            margin: 0.5rem 0;
        }

        .photo-container {
            text-align: center;
            margin-top: 1rem;
        }

        .photo {
            max-height: 150px;
            max-width: 150px;
            border-radius: 50%;
            border: 3px solid #e74c3c;
            object-fit: cover;
        }

        /* Footer Styles */
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .footer-logo img {
            height: 200px;
            margin-bottom: 1rem;
        }

        .footer-bottom {
            padding-top: 1rem;
            margin-top: 1rem;
            border-top: 1px solid #34495e;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                text-align: center;
            }
            
            .logo-container {
                margin-bottom: 1rem;
            }
            
            .nav-menu {
                flex-direction: column;
            }
            
            .owner-container {
                flex-direction: column;
                text-align: center;
            }
            
            .workers-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="header-container">
        <div class="logo-container">
            <img src="IMG/logo.png" alt="Sangam Auto Logo" class="logo">
            <div class="brand">
                <h1>Sangam Auto</h1>
                <p>Your trusted auto service partner</p>
            </div>
        </div>
        <div class="auth-buttons">
            <a href="login/login.php" class="btn login">Login</a>
            <a href="register/signup.php" class="btn register">Register</a>
        </div>
    </header>

    <!-- Navigation -->
   <nav style="display: flex; justify-content: center; align-items: center; width: 100%;" class="navbar">
    <ul class="nav-menu" style="list-style-type: none; padding: 0; margin: 0;">
        <li style="display: inline; margin: 0 10px;"><a href="homepage/hp.php">Home</a></li>
    </ul>
</nav>

    <!-- Main Content -->
    <main class="main-container">
        <div class="section-header">
            <h2>Contact Our Team</h2>
            <p>Get in touch with our dedicated professionals</p>
        </div>

        <!-- Owner Section -->
        <div class="owner-container">
            <div class="owner-details">
                <h3>Owner</h3>
                <p><strong>Name:</strong> Purushottam Shrestha</p>
                <p><strong>Email:</strong> sangamauto@gmail.com</p>
                <p><strong>Phone:</strong> +977 9823553889</p>
            </div>
            <div class="photo-container">
                <img src="IMG/owner_photo.jpg" alt="Owner Photo" class="owner-photo">
            </div>
        </div>

        <!-- Workers Section -->
        <div class="contact-container">
            <h3 style="text-align: center; color: #2c3e50; margin-bottom: 1.5rem;">Our Team</h3>
            <div class="workers-grid">
                <!-- Worker 1 Card -->
                <div class="contact-card">
                    <h3>Worker 1</h3>
                    <p><strong>Name:</strong> Amit Gurung</p>
                    <p><strong>Phone:</strong> +977 9823565533</p>
                    <div class="photo-container">
                        <img src="IMG/worker_photo1.jpg" alt="Worker Photo 1" class="photo">
                    </div>
                </div>

                <!-- Worker 2 Card -->
                <div class="contact-card">
                    <h3>Worker 2</h3>
                    <p><strong>Name:</strong> Ganesh Tamang</p>
                    <p><strong>Phone:</strong> +977 9813310956</p>
                    <div class="photo-container">
                        <img src="IMG/worker_photo2.jpg" alt="Worker Photo 2" class="photo">
                    </div>
                </div>

                <!-- Worker 3 Card -->
                <div class="contact-card">
                    <h3>Worker 3</h3>
                    <p><strong>Name:</strong> Badal Rai</p>
                    <p><strong>Phone:</strong> +977 9841123485</p>
                    <div class="photo-container">
                        <img src="IMG/worker_photo3.jpg" alt="Worker Photo 3" class="photo">
                    </div>
                </div>

                <!-- Worker 4 Card -->
                <div class="contact-card">
                    <h3>Worker 4</h3>
                    <p><strong>Name:</strong> Munna Alim</p>
                    <p><strong>Phone:</strong> +977 9886240786</p>
                    <div class="photo-container">
                        <img src="IMG/worker_photo4.jpg" alt="Worker Photo 4" class="photo">
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <img src="IMG/logo.png" alt="Sangam Auto Logo">
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Sangam Auto. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
