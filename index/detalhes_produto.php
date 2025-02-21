<?php
include 'ligabd.php'; // Conexão com o banco de dados

if (!isset($con) || $con->connect_error) {
    die("Erro na conexão com o banco de dados: " . $con->connect_error);
}

if (!isset($_GET['id'])) {
    header('Location: produtos.php');
    exit();
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM produtos WHERE id = ?";
$stmt = $con->prepare($sql);

if (!$stmt) {
    die("Erro na preparação da consulta: " . $con->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    header('Location: produtos.php');
    exit();
}

// Define a quantidade máxima com base no estoque
$maxQuantity = $product['quantidade'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['nome']); ?> - Detalhes</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --background-color: #f8f9fa;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition-speed: 0.3s;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            color: #333;
        }

        .product-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            background-color: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
        }

        .product-image {
            border-radius: 15px;
            overflow: hidden;
            position: relative;
            padding-top: 100%;
        }

        .product-image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .product-name {
            font-size: 2.5rem;
            font-weight: 600;
            margin: 0;
            color: #2d3436;
        }

        .product-price {
            font-size: 2rem;
            color: var(--primary-color);
            font-weight: 700;
            margin: 0;
        }

        .product-description {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #666;
        }

        .add-to-cart {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: var(--primary-color);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-speed);
            text-decoration: none;
        }

        .add-to-cart:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 1rem;
            transition: color var(--transition-speed);
        }

        .back-button:hover {
            color: #495057;
        }

        .error {
            color: red;
            font-size: 0.9rem;
            margin-top: 5px;
            display: none;
        }

        @media (max-width: 768px) {
            .product-container {
                grid-template-columns: 1fr;
                gap: 2rem;
                padding: 1rem;
                margin: 1rem;
            }

            .product-name {
                font-size: 2rem;
            }

            .product-price {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>

    <div class="main-container">
        <div class="product-container">
            <div class="product-image">
                <img src="<?php echo htmlspecialchars('utilizador/uploads/' . $product['imagem']); ?>" alt="<?php echo htmlspecialchars($product['nome']); ?>">
            </div>
            <div class="product-info">
                <a href="produtos.php" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    Voltar aos produtos
                </a>
                <h1 class="product-name"><?php echo htmlspecialchars($product['nome']); ?></h1>
                <p class="product-price">€<?php echo number_format($product['preco'], 2); ?></p>
                <p class="product-description"><?php echo htmlspecialchars($product['descricao']); ?></p>
                
                <!-- Campo de entrada para a quantidade -->
                <label for="quantity">Quantidade:</label>
                <input type="number" id="quantity" value="1" min="1" max="<?php echo $maxQuantity; ?>" />
                <span id="max-quantity" class="error">Quantidade máxima disponível: <?php echo $maxQuantity; ?></span>
                
                <button class="add-to-cart" onclick="addToCart(<?php echo $product['id']; ?>)">
                    <i class="fas fa-shopping-cart"></i>
                    Adicionar ao Carrinho
                </button>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('quantity').addEventListener('input', function() {
        let maxQuantity = <?php echo $maxQuantity; ?>;
        let quantity = parseInt(this.value);
        let errorSpan = document.getElementById('max-quantity');

        if (quantity > maxQuantity) {
            this.value = maxQuantity;
            errorSpan.style.display = 'block';
        } else {
            errorSpan.style.display = 'none';
        }
    });

    function addToCart(productId) {
        const quantity = document.getElementById('quantity').value;

        fetch('add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId + '&quantity=' + quantity
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Produto adicionado ao carrinho!');
            } else {
                alert('Erro ao adicionar produto ao carrinho.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao adicionar produto ao carrinho.');
        });
    }
    </script>
</body> 
</html>
<?php
$stmt->close();
$con->close();
?>
