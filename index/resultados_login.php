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
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $nome_servico = isset($_GET['nome']) ? $_GET['nome'] : '';
    $tipo_servico = isset($_GET['categoria']) ? $_GET['categoria'] : '';

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
    <title>Resultados da Pesquisa - Berto</title>
    <link rel="stylesheet" href="styles/all.css">
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/body.css">
    <link rel="stylesheet" href="styles/header2.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
</head>
<style>
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
        }.auth-buttons {
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
</style>

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

        <div class="profile-dropdown">
            <div onclick="toggle()" class="profile-dropdown-btn">
                <div class="profile-img">
                    <i class="fa-solid fa-circle"></i>
                </div>

                <span>
                    <?php echo htmlspecialchars($nome_usuario); ?>
                    <i class="fa-solid fa-angle-down"></i>
                </span>
            </div>

            <ul class="profile-dropdown-list">
                <li class="profile-dropdown-list-item">
                    <a href="utilizador/profile/index.php">
                        <i class="fa-regular fa-user"></i>
                        Edit Profile
                    </a>
                </li>

                <li class="profile-dropdown-list-item">
                    <a href="#">
                        <i class="fa-solid fa-sliders"></i>
                        Settings
                    </a>
                </li>

                <li class="profile-dropdown-list-item">
                    <a href="utilizador/gestao_produtos.php">
                        <i class="fa-regular fa-circle-question"></i>
                        Gestão de produtos
                    </a>
                </li>
                <hr />

                <li class="profile-dropdown-list-item">
                    <form id="logout-form" action="utilizador/logout.php" method="POST">
                        <input type="hidden" name="botaoLogout">
                        <a href="#" onclick="document.getElementById('logout-form').submit();">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            Log out
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    <script src="scripts/header.js"></script>

    <!-- Conteúdo Principal -->
    <main class="results-container">
        <section class="results-section">
            <h2>Resultados da Pesquisa</h2>
            <div class="results-grid">
                <?php if (!empty($servicos)): ?>
                    <?php foreach ($servicos as $servico): ?>
                        <div class="service-card">
                            <h3><?php echo htmlspecialchars($servico['nome']); ?></h3>
                            <p>Categoria: <?php echo htmlspecialchars($servico['categoria']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum serviço encontrado.</p>
                <?php endif; ?>
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
        /* Estilos para a página de resultados */
        .results-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .results-section {
            margin-bottom: 60px;
        }

        .results-section h2 {
            font-size: 2em;
            color: #333;
            margin-bottom: 30px;
        }

        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .service-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</body>

</html>