<?php
require "config_funcoes.php";
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8">
    <title>Exemplo de CSRF</title>
  </head>
  <body>
    <?php if (fHigienizaPost([
        'nome' => 'required'
      ], [
        'nome' => 'trim|sanitize_string'
      ])) :?>
      <p><a href="?">Voltar</a></p>
      <?php if (fCsrfTokenIsValidPost('cadastro_usuario')) :?>
        <p>Você informou o nome: <?=fE($_POST['nome'])?></p>
      <?php else: ?>
        <p>Você enviou um form sem token!</p>
      <?php endif ?>
    <?php elseif(fHigienizaGet([
        'versessao' => 'required|integer'
      ])): ?>
      <p><a href="?">Voltar</a></p>
      <pre><?=fE(var_dump($_SESSION))?></pre>
    <?php endif ?>

    <hr/>
    <form method="POST" action="?tipo=1">
      <label for="">Nome</label>
      <input name="nome" value="Para você <b>testar</b>"/>
      <button>Enviar, sem token</button>
    </form>

    <hr/>
    <form method="POST" action="?tipo=2">
      <?=fCsrfInputBuilder('cadastro_usuario')?>
      <label for="">Nome</label>
      <input name="nome" value="Para você <b>testar</b>"/>
      <button>Enviar, com token</button>
    </form>

    <hr>
    <a href="?versessao=1">Ver dados da sessão</a>
  </body>
</html>
