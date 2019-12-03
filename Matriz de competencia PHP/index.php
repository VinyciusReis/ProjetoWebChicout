<?php
// conecta ao banco
try {
  $conexao = new PDO("mysql:host=localhost;dbname=matriz", "root", "");
  $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conexao->exec("set names utf8");
} catch (PDOException $erro) {
  echo "Erro na conexão:".$erro->getMessage();
}

// verifica se mandou os dados via POST( envia para o banco )
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
  $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
  $setor = (isset($_POST["setor"]) && $_POST["setor"] != null) ? $_POST["setor"]:"";
  $competencia = (isset($_POST["competencia"]) && $_POST["competencia"] != null) ? $_POST["competencia"] : "";
}elseif(!isset($id)) {
  $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
  $nome = NULL;
  $setor = NULL;
  $competencia = NULL;
}

// CREATE  (enviar aqui)
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $nome != "") {
  try{
    if ($id != ""){
      $stmt = $conexao->prepare("UPDATE colaborador SET nome=?, setor=?, competencia=? WHERE id = ? ");
      $stmt->bindParam(4, $id);
    } else {
      $stmt = $conexao->prepare("INSERT INTO colaborador (nome, setor, competencia) VALUES (?, ?, ?)");
    }
    $stmt->bindParam(1, $nome);
    $stmt->bindParam(2, $setor);
    $stmt->bindParam(3, $competencia);

    if ($stmt->execute()){
      if ( $stmt->rowCount() > 0){
        echo "<p class='container'>Dados cadastrados com sucesso!<p>";
        $id = null;
        $nome = null;
        $setor = null;
        $competencia = null;
      } else {
        echo "Erro ao tentar efetivar cadastro";
      } 
    } else{
      throw new PDOException("Erro: não foi possivel executar a instrução sql");
    }
  } catch (PDOException $erro){
    echo "Erro:".$erro->getMessage();
  }
}

// UPDATE (mexer para atualizar)
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != ""){
  try{
    $stmt = $conexao->prepare("SELECT * FROM colaborador WHERE id = ?");
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    if ($stmt->execute()){
      $rs = $stmt->fetch(PDO::FETCH_OBS);
      $id = $rs->id;
      $nome = $rs->nome;
      $setor = $rs->setor;
      $competencia = $rs->competencia;
    } else {
      throw new PDOException("Erro: não foi possivel exectuar instrução sql");
    }
  } catch (PDOException $erro){
    echo "Erro: ".$erro->getMessage();
  }
}


//DELETE nao mexer 
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
  try {
    $stmt = $conexao->prepare("DELETE FROM colaborador WHERE id = ?");
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    if ($stmt->execute()){
      echo "<p class='container'>Registro excluido com exito <p/>";
      $id = null;
    } else {
      throw new PDOException("Erro: Nao foi possivel executar instrução sql");
    }
  } catch (PDOException $erro) {
    echo "Erro: ".$erro->getMessage();
  }
}
  # classe :: Calculadora
  class Calculadora {

    # Função "Calcular" para executar o cálculo dos valores (v1, v2 e v3)
    public function Calcular() {

        # Se for setado algum valor ào submit (doCalc), executa a operação
        if (isset($_POST['doCalc'])) {


                # Armazena a soma de [v1 + v2] na variável $resultado
                $resultado = $_POST['v1'] + $_POST['v2'] + $_POST['v3'];

                # Exibe a variável $resultado com os valores já somados
                return $resultado;

               
            } 
        }
    }



# Instancia a classe CALCULADORA()
$calcular = new Calculadora();

# Executa a função
//echo $calcular->Calcular();
 
//echo"<p>";
//    echo "A nota geral da competencia é :".$calcular->Calcular();
//echo"</p>";


?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Matriz de competência</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css">
</head>

<body class="row">
   <!-- div formulario -->
  <div class="container">
    <form action="?act=save" method="POST" name="form1" class="row">
      
      <h1>matriz de competência</h1>
        <!-- input para incerção de dados-->
      <input type="hidden" name="id" 
      
      <?php
                 
                // Preenche o id no campo id com um valor "value"
                if (isset($id) && $id != null || $id != "") {
                    echo "value=\"{$id}\"";
                }
                ?> 
                />
      Nome:
      <input type="text" name="nome" <?php
 
               // Preenche o nome no campo nome com um valor "value"
               if (isset($nome) && $nome != null || $nome != "") {
                   echo "value=\"{$nome}\"";
               }
               ?> />
      Setor:
      <input type="text" name="setor" <?php
 
               // Preenche o setor no campo email com um valor "value"
               if (isset($setor) && $setor != null || $setor != "") {
                   echo "value=\"{$setor}\"";
               }
               ?> />
      Competencias:
      <form method="POST" name="competencia" class="row">
            <!-- Input que receberá o primeiro valor a ser calculado -->
            
            <input type="text" placeholder="primeira competência">
            <input type="text" name="v1" placeholder="competencia 1" />

            <!-- Select com o tipo de operação (Somar, Diminuir, Multiplicar ou Dividir -->
            <select name="operacao">
                <option value="soma">+</option>
            </select>

            <!-- Input que receberá o segundo valor a ser calculado -->
            <input type="text" placeholder="segunda competência"  >
            <input type="text" name="v2" placeholder="competencia 2" />
            <select name="operacao">
                <option value="soma">+</option>
            </select>

            <!-- Input que receberá o terceiro valor a ser calculado -->
            <input type="text" placeholder="terceira competência"  >
            <input type="text" name="v3" placeholder="competencia 3" />

            <!-- Input que enviará os valores para a função de cálculo -->
           <input type="submit" name="doCalc" value="salvar" class="btn waves-effect waves-light" />
        </form>

    
  </div>
  <div class="container">
    <table class="striped">
      <tr>
        <th>Nome</th>
        <th>Setor</th>
        <th>Competencia</th>
        <th>Ações</th>
      </tr>
  </div>
  
  
  <?php
 
                // Bloco que realiza o papel do Read - recupera os dados e apresenta na tela
                try {
                    $stmt = $conexao->prepare("SELECT * FROM colaborador");
                    if ($stmt->execute()) {
                        while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                            echo "<tr>";

                            echo "<td>".$rs->nome."</td><td>".$rs->setor."</td><td>".$calcular->Calcular()
                                       ."</td><td><center><a href=\"?act=upd&id=".$rs->id."\">[Alterar]</a>"
                                       ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                       ."<a href=\"?act=del&id=".$rs->id."\">[Excluir]</a></center></td>";
                                       
                            echo "</tr>";
                        }
                    } else {
                        echo "Erro: Não foi possível recuperar os dados do banco de dados";
                    }
                } catch (PDOException $erro) {
                    echo "Erro: ".$erro->getMessage();
                }
                ?>
  </table>
  
</body>

</html>