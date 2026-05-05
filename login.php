<?php
include "conexao.php";

$mensagem = "";

if (isset($_POST['inserir'])) {

    $email = trim(strtolower($_POST['email']));
    $senha = trim($_POST['senha']);

    $erro = false;

    /* VALIDA EMAIL */
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem .= "<p class='erro'>Digite um e-mail válido.</p>";
        $erro = true;
    }

    /* VALIDA SENHA FORTE */
    $senhaForte = preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $senha);

    if (!$senhaForte) {
        $mensagem .= "<p class='erro'>A senha deve ter no mínimo 8 caracteres, com letra maiúscula, minúscula, número e símbolo.</p>";
        $erro = true;
    }

    if (!$erro) {

        // 🔥 HASH CORRETO
        $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $conexao->prepare("INSERT INTO cadastro (email, senha) VALUES (?, ?)");

        if (!$stmt) {
            die("Erro SQL: " . $conexao->error);
        }

        $stmt->bind_param("ss", $email, $senhaCriptografada);

        if ($stmt->execute()) {
            $mensagem = "<p class='sucesso'>Cadastro realizado com sucesso!</p>";
        } else {
            $mensagem = "<p class='erro'>Erro ao cadastrar: ".$stmt->error."</p>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Cadastro</title>

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

<h2>Cadastro</h2>

<?php echo $mensagem; ?>

<label>E-mail</label>
<input name="email" type="email" required>

<label>Senha</label>
<input name="senha" type="password" required>

<button type="submit" name="inserir">Cadastrar</button>

<a href="login.php">JÁ TENHO CONTA</a>

</form>

</div>

</body>
</html>
