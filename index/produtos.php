<?php
session_start();

// Verifica se o usuário está logado
$nome_usuario = isset($_SESSION["utilizador"]) ? $_SESSION["utilizador"] : "Visitante";

// Conexão com o banco de dados
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "gestao_utilizadores";

$conn = new mysqli($servidor, $usuario, $senha, $banco);

// Verifica conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Captura o ID do utilizador logado
$id_utilizador = $_SESSION['id_utilizadores'];
$id_tipos_utilizador = $_SESSION['id_tipos_utilizador'];

// Inicializa a variável de pesquisa
$search_query = "";

// Verifica se a pesquisa foi feita
if (isset($_POST['search'])) {
    $search_query = $conn->real_escape_string($_POST['search']);
}

// Consulta para obter produtos
if ($id_tipos_utilizador == 0) {
    $sql = "SELECT * FROM produtos WHERE nome LIKE '%$search_query%'";
} else {
    $sql = "SELECT * FROM produtos WHERE id_utilizador != $id_utilizador AND nome LIKE '%$search_query%'";
}

$result = $conn->query($sql);

// Contagem total de produtos no carrinho
$total_cart_count = 0;
if (isset($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $product_id => $quantity) {
        $total_cart_count += $quantity; // Soma a quantidade de cada produto
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <link rel="stylesheet" href="styles/all.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/body.css">
    <link rel="stylesheet" href="styles/header2.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <style>
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #f8f9fa;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --background-color: #f8f9fa;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition-speed: 0.3s;
        }

        body {
            background-color: var(--background-color);
            color: #333;
            font-family: 'Poppins', sans-serif;
        }

        .main-container {
            width: 100%;
            margin: 0 auto;
            padding: 2rem;
        }

        .search-section {
            width: 100%;
            max-width: 1000px;
            margin: 0rem auto;
        }

        .search-container {
            width: 100%;
        }

        .search-container input {
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            background-color: white;
            transition: all var(--transition-speed) ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .search-container input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.15);
            outline: none;
        }

        .search-results {
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            margin-top: 0.5rem;
            z-index: 1000;
            max-height: 400px;
            overflow-y: auto;
            display: none;
        }

        .search-result-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #eee;
            transition: background-color var(--transition-speed);
        }

        .search-result-item:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .search-result-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 1rem;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
            padding: 2rem;
            width: 100%;
            max-width: 1800px;
            margin: 0 auto;
        }

        .product-card {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: transform var(--transition-speed), box-shadow var(--transition-speed);
            cursor: pointer;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            width: 100%;
            padding-top: 100%;
            /* 1:1 Aspect Ratio */
            position: relative;
            overflow: hidden;
        }

        .product-image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-speed);
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-info {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .product-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2d3436;
            margin: 0;
        }

        .product-price {
            font-size: 1.3rem;
            color: var(--primary-color);
            font-weight: 700;
            margin: 0;
        }

        .product-cart-quantity {
            font-size: 1rem;
            color: #6c757d;
        }

        .cart-icon {
            position: fixed;
            top: 100px;
            right: 30px;
            background: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform var(--transition-speed);
            z-index: 1000;
        }

        .cart-icon:hover {
            transform: scale(1.1);
        }

        .cart-icon i {
            font-size: 24px;
            color: var(--primary-color);
        }

        .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            font-weight: 600;
            min-width: 20px;
            text-align: center;
        }

        @media (max-width: 1400px) {
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 1rem;
                padding: 1rem;
            }

            .product-info {
                padding: 1rem;
            }

            .product-name {
                font-size: 1rem;
            }

            .product-price {
                font-size: 1.1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <h1>Berto</h1>
        <ul class="navbar-list">
            <li><a href="#">inicio</ a>
            </li>
            <li><a href="produtos.php">produtos</a></li>
            <li><a href="#">serviços</a></li>
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



    <div class="main-container">
        <!-- Search Section -->
        <div style="display: flex; justify-content: space-between; align-items: center; position: fixed; z-index: 1000;">
            <div class="search-section">
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Pesquisar produtos..." autocomplete="off">
                    <div class="search-results" id="searchResults"></div>
                </div>
            </div>

            <!-- Cart Icon -->
            <div class="cart-icon">
                <a href="cart.php">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count"><?php echo $total_cart_count; ?></span>
                </a>
            </div>
        </div>

        <div style="margin-top: 100px;"></div>

        <!-- Product Grid -->
        <div class="product-grid">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $product_id = $row['id'];
                    $cart_quantity = $_SESSION['cart'][$product_id] ?? 0;
                    echo "<a href='detalhes_produto.php?id={$product_id}' class='product-card'>
                        <div class='product-image'>
                            <img src='utilizador/uploads/{$row['imagem']}' alt='{$row['nome']}'>
                        </div>
                        <div class='product-info'>
                            <h3 class='product-name'>{$row['nome']}</h3>
                            <p class='product-price'>{$row['preco']} €</p>
                            <p class='product-cart-quantity'>Quantidade no carrinho: {$cart_quantity}</p>
                        </div>
                    </a>";
                }
            } else {
                echo "<div class='no-products'>
                        <p>Nenhum produto encontrado.</p>
                      </div>";
            }
            ?>
        </div>
    </div>

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
                        <li><a href="#">dress </a></li>
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

    <script>
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');

        let searchTimeout;

        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const query = this.value;

            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`search_products.php?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        searchResults.innerHTML = '';
                        data.forEach(product => {
                            const div = document.createElement('div');
                            div.className = 'search-result-item';
                            div.innerHTML = `
                            <img src="utilizador/uploads${product.imagem}" alt="${product.nome}">
                            <div class="search-result-info">
                                <div class="search-result-name">${product.nome}</div>
                                <div class="search-result-price">€${product.preco}</div>
                            </div>
                        `;
                            div.onclick = () => window.location.href = `detalhes_produto.php?id=${product.id}`;
                            searchResults.appendChild(div);
                        });
                        searchResults.style.display = data.length ? 'block' : 'none';
                    });
            }, 300);
        });

        document.addEventListener('click', function (e) {
            if (!searchResults.contains(e.target) && e.target !== searchInput) {
                searchResults.style.display = 'none';
            }
        });

        function addToCart(productId) {
            fetch('cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=add&product_id=${productId}&quantity=1`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const cartCount = document.querySelector('.cart-count');
                        cartCount.textContent = parseInt(cartCount.textContent) + 1;
                        alert('Produto adicionado ao carrinho!');
                    }
                });
        }
    </script>
</body>

</html>

<?php
$conn->close();
?>