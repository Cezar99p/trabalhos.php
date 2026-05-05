<?php
include "conexao.php";

$mensagem = "";

if (isset($_POST['login'])) {

    $email = trim(strtolower($_POST['email']));
    $senha = trim($_POST['senha']);

    $stmt = $conexao->prepare("SELECT email, senha FROM cadastro WHERE email = ?");

    if (!$stmt) {
        die("Erro SQL: " . $conexao->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($row = $resultado->fetch_assoc()) {

        // 🔥 CORREÇÃO REAL DO PROBLEMA
        if (password_verify($senha, $row['senha'])) {
            $mensagem = "<p class='sucesso'>Login realizado com sucesso!</p>";
        } else {
            $mensagem = "<p class='erro'>Senha incorreta.</p>";
        }

    } else {
        $mensagem = "<p class='erro'>Usuário não encontrado.</p>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login</title>

<style>
body{background:#f5ecd9;font-family:"Times New Roman", serif;}
.container{width:380px;margin:120px auto;}
form{background:#fffaf0;padding:35px;border:3px solid #5a3b1c;box-shadow:4px 4px 10px rgba(0,0,0,0.3);}
h2{text-align:center;color:#3b2a1a;margin-bottom:25px;}
label{font-weight:bold;color:#3b2a1a;}
input{width:100%;padding:10px;margin-top:5px;margin-bottom:18px;border:1px solid #5a3b1c;background:#fffdf7;font-family:"Times New Roman", serif;}
button{width:100%;padding:12px;background:#5a3b1c;color:white;border:none;font-size:16px;cursor:pointer;}
button:hover{background:#3b2a1a;}
.erro{color:#8b0000;font-weight:bold;text-align:center;margin-bottom:10px;}
.sucesso{color:green;font-weight:bold;text-align:center;margin-bottom:10px;}
</style>

</head>

<body>

<div class="container">

<form method="post">

<h2>Login</h2>

<?php echo $mensagem; ?>

<label>E-mail</label>
<input name="email" type="email" required>

<label>Senha</label>
<input name="senha" type="password" required>

<button type="submit" name="login">Entrar</button>

</form>

</div>

</body>
</html>
