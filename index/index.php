<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berto - Encontre Ajuda para Qualquer Tarefa</title>
    <link rel="stylesheet" href="styles/all.css">
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/body.css">
    <link rel="stylesheet" href="styles/header2.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />    
</head>

<body>
    <nav class="navbar">
        <h1>Berto</h1>
        <ul class="navbar-list">
          <li><a href="#" class="active">inicio</a></li>
          <li><a href="produtos.php">produtos</a></li>
          <li><a href="serviços.php">serviços</a></li>
          <li><a href="#">sobre</a></li>
        </ul>

        <div class="auth-buttons">
            <a href="logintexte.php" class="auth-btn login-btn">Login</a>
            <a href="registop2.php" class="auth-btn register-btn">Registo</a>
        </div>
    </nav>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1>Conectamos pessoas a soluções</h1>
                <p>Encontre ajuda para qualquer tarefa ou ofereça seus serviços</p>
                <div class="hero-buttons">
                    <a href="serviços.php" class="btn btn-primary">Procurar Serviços</a>
                    <a href="registop2.php" class="btn btn-secondary">Tornar-se Prestador</a>
                </div>
            </div>
        </section>

        <!-- Destaques -->
        <section class="highlights">
            <div class="container">
                <div class="highlight-card">
                    <i class="fas fa-users"></i>
                    <h3>+1000</h3>
                    <p>Prestadores Ativos</p>
                </div>
                <div class="highlight-card">
                    <i class="fas fa-check-circle"></i>
                    <h3>+5000</h3>
                    <p>Serviços Realizados</p>
                </div>
                <div class="highlight-card">
                    <i class="fas fa-star"></i>
                    <h3>4.8/5</h3>
                    <p>Avaliação Média</p>
                </div>
            </div>
        </section>

        <!-- Como Funciona -->
        <section class="how-it-works">
            <div class="container">
                <h2>Como Funciona</h2>
                <div class="steps">
                    <div class="step">
                        <div class="step-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>Encontre</h3>
                        <p>Busque o serviço que precisa entre diversas categorias</p>
                    </div>
                    <div class="step">
                        <div class="step-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3>Conecte</h3>
                        <p>Entre em contato e combine os detalhes</p>
                    </div>
                    <div class="step">
                        <div class="step-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3>Avalie</h3>
                        <p>Compartilhe sua experiência com a comunidade</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Categorias Populares -->
        <section class="popular-categories">
            <div class="container">
                <h2>Categorias Populares</h2>
                <div class="categories">
                    <a href="servicos.php?cat=domesticos" class="category">
                        <i class="fas fa-home"></i>
                        <span>Serviços Domésticos</span>
                    </a>
                    <a href="servicos.php?cat=assistencia" class="category">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Assistência Pessoal</span>
                    </a>
                    <a href="servicos.php?cat=manutencao" class="category">
                        <i class="fas fa-tools"></i>
                        <span>Manutenção</span>
                    </a>
                    <a href="servicos.php?cat=tecnologia" class="category">
                        <i class="fas fa-laptop"></i>
                        <span>Tecnologia</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- Depoimentos -->
        <section class="testimonials">
            <div class="container">
                <h2>O que dizem nossos usuários</h2>
                <div class="testimonials-grid">
                    <div class="testimonial">
                        <div class="testimonial-content">
                            <p>"Encontrei um ótimo jardineiro para cuidar do meu quintal. Serviço excelente e preço justo!"</p>
                        </div>
                        <div class="testimonial-author">
                            <img src="https://via.placeholder.com/50" alt="Ana">
                            <div>
                                <h4>Ana Silva</h4>
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial">
                        <div class="testimonial-content">
                            <p>"Como eletricista, encontro diversos trabalhos através da plataforma. Muito prático!"</p>
                        </div>
                        <div class="testimonial-author">
                            <img src="https://via.placeholder.com/50" alt="Pedro">
                            <div>
                                <h4>Pedro Santos</h4>
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="cta">
            <div class="container">
                <h2>Pronto para começar?</h2>
                <p>Junte-se a milhares de pessoas que já confiam em nossa plataforma</p>
                <div class="cta-buttons">
                    <a href="registop2.php" class="btn btn-primary">Criar Conta Grátis</a>
                    <a href="serviços.php" class="btn btn-secondary">Explorar Serviços</a>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="footer-col">
                    <h4>company</h4>
                    <ul>
                        <li><a href="#">about us</a></li>
                        <li><a href="#">our services</a></li>
                        <li><a href="#">privacy policy</a></li>
                        <li><a href="#">affiliate program</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>get help</h4>
                    <ul>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">shipping</a></li>
                        <li><a href="#">returns</a></li>
                        <li><a href="#">order status</a></li>
                        <li><a href="#">payment options</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>online shop</h4>
                    <ul>
                        <li><a href="#">watch</a></li>
                        <li><a href="#">bag</a></li>
                        <li><a href="#">shoes</a></li>
                        <li><a href="#">dress</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>follow us</h4>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <style>
        /* Reset e estilos base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Navbar existente */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #f8f9fa;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-list {
            list-style: none;
            display: flex;
            gap: 25px;
            margin-left: 40px;
            padding: 0;
        }

        .navbar-list a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        .navbar-list a.active {
            color: #28a745;
        }

        .auth-buttons {
            display: flex;
            gap: 10px;
        }

        .auth-btn {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .login-btn {
            background-color: #28a745;
        }

        .register-btn {
            background-color: #218838;
        }

        .auth-btn:hover {
            opacity: 0.8;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(40, 167, 69, 0.9), rgba(33, 136, 56, 0.9)), url('https://images.unsplash.com/photo-1521791136064-7986c2920216?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 100px 20px;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 3em;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 1.2em;
            margin-bottom: 30px;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: #28a745;
            color: white;
        }

        .btn-secondary {
            background-color: white;
            color: #28a745;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        /* Highlights */
        .highlights {
            background-color: white;
            padding: 60px 0;
        }

        .highlights .container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            text-align: center;
        }

        .highlight-card {
            padding: 20px;
        }

        .highlight-card i {
            font-size: 2.5em;
            color: #28a745;
            margin-bottom: 15px;
        }

        .highlight-card h3 {
            font-size: 2em;
            color: #333;
            margin-bottom: 10px;
        }

        /* Como Funciona */
        .how-it-works {
            padding: 80px 0;
            background-color: #f8f9fa;
        }

        .how-it-works h2 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 50px;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            text-align: center;
        }

        .step-icon {
            width: 80px;
            height: 80px;
            background-color: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .step-icon i {
            font-size: 2em;
            color: white;
        }

        /* Categorias Populares */
        .popular-categories {
            padding: 80px 0;
            background-color: white;
        }

        .popular-categories h2 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 50px;
        }

        .categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .category {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 10px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
        }

        .category:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .category i {
            font-size: 2em;
            color: #28a745;
            margin-bottom: 15px;
        }

        /* Depoimentos */
        .testimonials {
            padding: 80px 0;
            background-color: #f8f9fa;
        }

        .testimonials h2 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 50px;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .testimonial {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .testimonial-content {
            margin-bottom: 20px;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .testimonial-author img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .rating {
            color: #ffc107;
        }

        /* CTA */
        .cta {
            padding: 80px 0;
            background-color: #28a745;
            color: white;
            text-align: center;
        }

        .cta h2 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        .cta p {
            font-size: 1.2em;
            margin-bottom: 30px;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2em;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .steps, .categories {
                grid-template-columns: 1fr;
            }

            .testimonials-grid {
                grid-template-columns: 1fr;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</body>

</html>