<?php
session_start();

// Verifica se o usuário está logado
$nome_usuario = isset($_SESSION["utilizador"]) ? $_SESSION["utilizador"] : "Visitante";

// Conexão com a base de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestao_utilizadores";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Processa a pesquisa
$servicos = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_servico = $_POST['nome'];
    $tipo_servico = $_POST['categoria'];

    $sql = "SELECT * FROM servicos WHERE nome LIKE ? AND categoria LIKE ?";
    $stmt = $conn->prepare($sql);
    $nome_servico = "%" . $nome_servico . "%";
    $tipo_servico = $tipo_servico ? $tipo_servico : "%";
    $stmt->bind_param("ss", $nome_servico, $tipo_servico);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $servicos[] = $row;
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviços - Berto</title>
    <link rel="stylesheet" href="styles/all.css">
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/body.css">
    <link rel="stylesheet" href="styles/header2.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <h1>Berto</h1>
        <ul class="navbar-list">
            <li><a href="#">inicio</a></li>
            <li><a href="produtos.php">produtos</a></li>
            <li><a href="servicos.php" class="active">serviços</a></li>
            <li><a href="#">sobre</a></li>
        </ul>

        <div class="auth-buttons">
            <a href="logintexte.php" class="auth-btn login-btn">Login</a>
            <a href="registop2.php" class="auth-btn register-btn">Registo</a>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="services-container">
        <!-- Seção de Pesquisa -->
        <section class="search-section">
            <h1>Encontre ajuda para qualquer tarefa</h1>
            <form method="GET" action="resultados.php" class="search-box">
                <input type="text" name="nome" placeholder="Do que você precisa? Ex: cortar relva, fazer compras...">
                <select name="categoria">
                    <option value="">Todos os tipos</option>
                    <option value="casa">Serviços Domésticos</option>
                    <option value="digital">Serviços Digitais</option>
                    <option value="assistencia">Assistência Pessoal</option>
                    <option value="manutencao">Manutenção</option>
                    <option value="eventos">Eventos</option>
                    <option value="Aulas e Treinos">Aulas e Treinos</option>
                </select>
                <button type="submit" class="search-btn">Buscar</button>
            </form>
        </section>



        <!-- Categorias Populares -->
        <section class="categories-section">
            <h2>O que você precisa?</h2>
            <div class="categories-grid">
                <div class="category-card">
                    <i class="fas fa-home"></i>
                    <h3>Serviços Domésticos</h3>
                    <p>Limpeza, Jardinagem, Reparos</p>
                    <span>210 prestadores</span>
                </div>
                <div class="category-card">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Assistência Pessoal</h3>
                    <p>Compras, Entregas, Recados</p>
                    <span>185 prestadores</span>
                </div>
                <div class="category-card">
                    <i class="fas fa-laptop"></i>
                    <h3>Serviços Digitais</h3>
                    <p>Design, Programação, Marketing</p>
                    <span>150 prestadores</span>
                </div>
                <div class="category-card">
                    <i class="fas fa-tools"></i>
                    <h3>Manutenção</h3>
                    <p>Eletricista, Encanador, Pintor</p>
                    <span>95 prestadores</span>
                </div>
                <div class="category-card">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>Aulas e Treinos</h3>
                    <p>Professores, Personal Trainers</p>
                    <span>78 prestadores</span>
                </div>
                <div class="category-card">
                    <i class="fas fa-glass-cheers"></i>
                    <h3>Eventos</h3>
                    <p>Fotografia, Buffet, Decoração</p>
                    <span>65 prestadores</span>
                </div>
            </div>
        </section>

        <!-- Profissionais em Destaque -->
        <section class="featured-section">
            <h2>Prestadores em Destaque</h2>
            <div class="professionals-grid">
                <div class="professional-card">
                    <div class="professional-header">
                        <img src="https://via.placeholder.com/60" alt="Foto do profissional" class="profile-pic">
                        <div class="professional-info">
                            <h3>João Silva</h3>
                            <p>Jardinagem e Manutenção</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <span>(4.5)</span>
                            </div>
                        </div>
                    </div>
                    <p class="professional-description">Serviços de jardinagem, corte de relva e manutenção geral de
                        jardins. Experiência de 8 anos.</p>
                    <div class="professional-footer">
                        <span class="price">A partir de R$ 50/hora</span>
                        <a href="#" class="contact-btn">Contactar</a>
                    </div>
                </div>

                <div class="professional-card">
                    <div class="professional-header">
                        <img src="https://via.placeholder.com/60" alt="Foto do profissional" class="profile-pic">
                        <div class="professional-info">
                            <h3>Maria Santos</h3>
                            <p>Assistente Pessoal</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <span>(5.0)</span>
                            </div>
                        </div>
                    </div>
                    <p class="professional-description">Auxílio com compras, organização, pagamentos e outras tarefas do
                        dia a dia.</p>
                    <div class="professional-footer">
                        <span class="price">A partir de R$ 40/hora</span>
                        <a href="#" class="contact-btn">Contactar</a>
                    </div>
                </div>

                <div class="professional-card">
                    <div class="professional-header">
                        <img src="https://via.placeholder.com/60" alt="Foto do profissional" class="profile-pic">
                        <div class="professional-info">
                            <h3>Carlos Oliveira</h3>
                            <p>Eletricista</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <span>(4.0)</span>
                            </div>
                        </div>
                    </div>
                    <p class="professional-description">Instalações elétricas, reparos e manutenção residencial e
                        comercial.</p>
                    <div class="professional-footer">
                        <span class="price">A partir de R$ 80/hora</span>
                        <a href="#" class="contact-btn">Contactar</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Como Funciona -->
        <section class="how-it-works">
            <h2>Como Funciona</h2>
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <i class="fas fa-search"></i>
                    <h3>Procure</h3>
                    <p>Encontre o prestador ideal para sua necessidade</p>
                </div>
                <div class="step-card">
                    <div class="step-number">2</div>
                    <i class="fas fa-comments"></i>
                    <h3>Converse</h3>
                    <p>Combine os detalhes do serviço e o preço</p>
                </div>
                <div class="step-card">
                    <div class="step-number">3</div>
                    <i class="fas fa-check-circle"></i>
                    <h3>Contrate</h3>
                    <p>Contrate e avalie o serviço realizado</p>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
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
        /* Estilos existentes da navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #f8f9fa;
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

        /* Estilos para a página de serviços */
        .services-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* Seção de Pesquisa */
        .search-section {
            text-align: center;
            margin-bottom: 60px;
        }

        .search-section h1 {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 30px;
        }

        .search-box {
            display: flex;
            gap: 10px;
            max-width: 800px;
            margin: 0 auto;
        }

        .search-box input,
        .search-box select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        .search-box input {
            flex: 1;
        }

        .search-btn {
            padding: 12px 30px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-btn:hover {
            background-color: #218838;
        }

        /* Categorias */
        .categories-section {
            margin-bottom: 60px;
        }

        .categories-section h2 {
            font-size: 2em;
            color: #333;
            margin-bottom: 30px;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .category-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .category-card:hover {
            transform: translateY(-5px);
        }

        .category-card i {
            font-size: 2em;
            color: #28a745;
            margin-bottom: 15px;
        }

        .category-card h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .category-card p {
            color: #666;
            margin-bottom: 10px;
            font-size: 0.9em;
        }

        .category-card span {
            color: #28a745;
            font-size: 0.8em;
            font-weight: bold;
        }

        /* Profissionais em Destaque */
        .featured-section {
            margin-bottom: 60px;
        }

        .featured-section h2 {
            font-size: 2em;
            color: #333;
            margin-bottom: 30px;
        }

        .professionals-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .professional-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .professional-header {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .profile-pic {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .professional-info h3 {
            color: #333;
            margin: 0;
        }

        .professional-info p {
            color: #666;
            margin: 5px 0;
        }

        .rating {
            color: #ffc107;
        }

        .rating span {
            color: #666;
            margin-left: 5px;
        }

        .professional-description {
            color: #666;
            margin-bottom: 20px;
        }

        .professional-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price {
            color: #28a745;
            font-weight: bold;
        }

        .contact-btn {
            padding: 8px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .contact-btn:hover {
            background-color: #218838;
        }

        /* Como Funciona */
        .how-it-works {
            margin-bottom: 60px;
        }

        .how-it-works h2 {
            font-size: 2em;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
        }

        .step-card {
            text-align: center;
            padding: 20px;
            position: relative;
        }

        .step-number {
            width: 30px;
            height: 30px;
            background-color: #28a745;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }

        .step-card i {
            font-size: 2em;
            color: #28a745;
            margin-bottom: 15px;
        }

        .step-card h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .step-card p {
            color: #666;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .search-box {
                flex-direction: column;
            }

            .professionals-grid {
                grid-template-columns: 1fr;
            }

            .search-section h1 {
                font-size: 2em;
            }
        }
    </style>
</body>

</html>