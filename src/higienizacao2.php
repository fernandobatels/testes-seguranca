<?php
require "config_funcoes.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8">
    <title>Exemplo de higienização 2</title>
  </head>
  <body>
<?php
fHigienizaGet(['tipo' => 'required|integer']);

if ($_GET['tipo'] == 1 && fHigienizaPost(null, [
    'nome' => 'trim|sanitize_string'
  ])):
?>
    <p><a href="?">Voltar</a></p>
    <p>Você informou o nome: <?=$_POST['nome'] ?: 'Não, não informou :('?></p>
<?php
elseif ($_GET['tipo'] == 2 && fHigienizaPost([
  'email' => 'required|valid_email'
], [
  'email' => 'trim',
  'email2' => 'trim|sanitize_email'
])):
?>
    <p><a href="?">Voltar</a></p>
    <p>Você informou o e-mail obrigatório: <?=$_POST['email']?></p>
    <p>Você informou o e-mail: <?=$_POST['email2']?></p>
<?php endif ?>

    <hr/>
    <form method="POST" action="?tipo=1">
      <label for="">Nome</label>
      <input name="nome" value="Para você <b>testar</b>"/>
      <button>Enviar</button>
    </form>

    <hr/>
    <form method="POST" action="?tipo=2">
      <label for="">E-mail obrigatório</label>
      <input name="email" value="luisfbatels@gmail.com"/>
      <label for="">E-mail</label>
      <input name="email2" value="luisfba tels@ gmail.com"/>
      <button>Enviar</button>
    </form>
  </body>
</html>
