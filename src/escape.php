<?php
  require "config_funcoes.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8">
    <title>Exemplo de escape</title>
  </head>
  <body>
<?php
  if (fHigienizaGet([
    'id' => 'required|integer'
  ], [
    'nome' => 'trim|sanitize_string'
  ])):
?>
  <p><a href="?">Voltar</a></p>
  <p>Você informou o id: <?=fE($_GET['id'])?></p>
  <p>Você informou o nome: <?=fE($_GET['nome']) ?: 'Não, não informou :('?></p>
  <p>Você informou o nome, com XSS: <?=fE($_GET['nome_xss']) ?: 'Não, não informou :('?></p>
<?php else: ?>
    <ul>
      <li>
        <a href="?id=15">Link com ID correto</a>
      </li>
      <li>
        <a href="?id=15&nome=Luis Fernando Batels">Link com ID correto + nome</a>
      </li>
      <li>
        <a href="?id=15&nome=Luis<script>alert('Você foi hakeado, não pera :(');</script> Fernando Batels&nome_xss=<script>alert('Você foi hackeado!');</script>">Link com ID correto + nome + xss</a>
      </li>

      <li>
        <a href="?id=Injetando">Link com ID incorreto</a>
      </li>
    </ul>
<?php endif ?>
  </body>
</html>
