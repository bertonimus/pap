<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exemplo de Navegação</title>
    <link rel="stylesheet" href="styles/all.css">
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/body.css">
</head>

<body>
    <nav class="navbar">
        <div class="navdiv">
            <div class="logo"><a href="#">Berto</a></div>
            <ul>
                <li><a href="#">inicio</a></li>
                <li><a href="#">Sobre</a></li>
                <li><a href="produtos.php">Produtos</a></li>
                <button><a href="administrador/editar_utilizadores.php">gestão site </a></button>
                
                <!-- Botão de logout com formulário -->
                <form action="utilizador/logout.php" method="POST" style="display: inline;">
                    <button type="submit" name="botaoLogout">Logout</button>
                </form>
            </ul>
        </div>
    </nav>

    <main>
        <!-- Conteúdo principal -->
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
</body>

</html>
