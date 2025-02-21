<?php
session_start();

// Database connection
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "gestao_utilizadores";

$conn = new mysqli($servidor, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Connection failed']));
}

// Verifica se o usuário está logado
if (!isset($_SESSION['id_utilizadores'])) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['id_utilizadores'];

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $product_id = $_POST['product_id'] ?? 0;
    $quantity = $_POST['quantity'] ?? 1;

    switch ($action) {
        case 'add':
            $stmt = $conn->prepare("INSERT INTO carrinho (usuario_id, produto_id, quantidade) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantidade = quantidade + ?");
            $stmt->bind_param("iiii", $usuario_id, $product_id, $quantity, $quantity);
            $stmt->execute();
            $stmt->close();
            echo json_encode(['success' => true]);
            break;

        case 'update':
            if ($quantity > 0) {
                $stmt = $conn->prepare("UPDATE carrinho SET quantidade = ? WHERE usuario_id = ? AND produto_id = ?");
                $stmt->bind_param("iii", $quantity, $usuario_id, $product_id);
                $stmt->execute();
                $stmt->close();
            } else {
                $stmt = $conn->prepare("DELETE FROM carrinho WHERE usuario_id = ? AND produto_id = ?");
                $stmt->bind_param("ii", $usuario_id, $product_id);
                $stmt->execute();
                $stmt->close();
            }
            echo json_encode(['success' => true]);
            break;

        case 'remove':
            $stmt = $conn->prepare("DELETE FROM carrinho WHERE usuario_id = ? AND produto_id = ?");
            $stmt->bind_param("ii", $usuario_id, $product_id);
            $stmt->execute();
            $stmt->close();
            echo json_encode(['success' => true]);
            break;

        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
    }
    exit;
}

// Handle GET requests (display cart)
$cart_items = [];
$sql = "SELECT c.quantidade, p.id, p.nome, p.preco, p.imagem 
        FROM carrinho c 
        JOIN produtos p ON c.produto_id = p.id 
        WHERE c.usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $row['total'] = $row['preco'] * $row['quantidade'];
    $cart_items[] = $row;
}

// Buscar nome do usuário
$stmt = $conn->prepare("SELECT utilizador FROM utilizadores WHERE id_utilizadores = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $nome_usuario = $row['utilizador'];
} else {
    $nome_usuario = "Usuário"; // Nome padrão caso não encontre
}


$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="styles/all.css">
    <link rel="stylesheet" href="styles/header2.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <style>
        .cart-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #eee;
        }

        .cart-header h2 {
            font-size: 1.8rem;
            color: #333;
            margin: 0;
        }

        .cart-item {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 2rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .cart-item img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .cart-item-details {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .cart-item-details h3 {
            font-size: 1.2rem;
            color: #333;
            margin: 0 0 0.5rem 0;
        }

        .price-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #666;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin: 1rem 0;
        }

        .quantity-controls button {
            padding: 0.5rem 1rem;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .quantity-controls button:hover {
            background: #0056b3;
        }

        .quantity {
            font-size: 1.1rem;
            font-weight: 600;
            min-width: 2rem;
            text-align: center;
        }

        .remove-btn {
            background: #dc3545 !important;
            padding: 0.5rem 1rem;
        }

        .remove-btn:hover {
            background: #c82333 !important;
        }

        .cart-summary {
            margin-top: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .cart-summary h3 {
            color: #333;
            margin: 0 0 1rem 0;
        }

        .cart-total {
            font-size: 1.4rem;
            font-weight: 600;
            color: #28a745;
            margin: 1rem 0;
        }

        .checkout-btn {
            width: 100%;
            padding: 1rem;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .checkout-btn:hover {
            background: #218838;
        }

        .empty-cart {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .empty-cart p {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
        }

        .continue-shopping {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s ease;
        }

        .continue-shopping:hover {
            background: #0056b3;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .cart-item {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .cart-item img {
                margin: 0 auto;
            }

            .quantity-controls {
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <h1>Berto</h1>
        <ul class="navbar-list">
            <li><a href="#">inicio</a></li>
            <li><a href="produtos.php">produtos</a></li>
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

    <div class="cart-container">
        <div class="cart-header">
            <h2>Carrinho de Compras</h2>
        </div>

        <?php if (empty($cart_items)): ?>
            <div class="empty-cart">
                <p>Seu carrinho está vazio</p>
                <a href="produtos.php" class="continue-shopping">Continuar Comprando</a>
            </div>
        <?php else: ?>
            <?php
            $grouped_items = [];
            foreach ($cart_items as $item) {
                if (!isset($grouped_items[$item['id']])) {
                    $grouped_items[$item['id']] = $item;
                } else {
                    $grouped_items[$item['id']]['quantidade'] += $item['quantidade'];
                    $grouped_items[$item['id']]['total'] += $item['total'];
                }
            }
            foreach ($grouped_items as $item): ?>
                <div class="cart-item" data-id="<?php echo $item['id']; ?>">
                    <img src="utilizador/uploads/<?php echo $item['imagem']; ?>" alt="<?php echo $item['nome']; ?>">
                    <div class="cart-item-details">
                        <h3><?php echo $item['nome']; ?></h3>
                        <div class="price-info">
                            <span>Preço unitário: €<?php echo number_format($item['preco'], 2); ?></span>
                            <span>|</span>
                            <span>Total: €<?php echo number_format($item['total'], 2); ?></span>
                        </div>
                        <div class="quantity-controls">
                            <button onclick="updateQuantity(<?php echo $item['id']; ?>, -1)">-</button>
                            <span class="quantity"><?php echo $item['quantidade']; ?></span>
                            <button onclick="updateQuantity(<?php echo $item['id']; ?>, 1)">+</button>
                            <button class="remove-btn" onclick="removeItem(<?php echo $item['id']; ?>)">
                                <i class="fas fa-trash"></i> Remover
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="cart-summary">
                <h3>Resumo do Pedido</h3>
                <div class="cart-total">
                    Total: €<?php echo number_format(array_sum(array_column($grouped_items, 'total')), 2); ?>
                </div>
                <button class="checkout-btn" onclick="checkout()">
                    <i class="fas fa-shopping-cart"></i> Finalizar Compra
                </button>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function updateQuantity(productId, change) {
            const item = document.querySelector(`.cart-item[data-id="${productId}"]`);
            const quantitySpan = item.querySelector('.quantity');
            let newQuantity = parseInt(quantitySpan.textContent) + change;

            if (newQuantity < 1) newQuantity = 1;

            fetch('cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=update&product_id=${productId}&quantity=${newQuantity}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }

        function removeItem(productId) {
            if (confirm('Tem certeza que deseja remover este item?')) {
                fetch('cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=remove&product_id=${productId}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            }
        }

        function checkout() {
            window.location.href = 'checkout.php';
        }

    </script>
</body>

</html>