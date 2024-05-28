<!-- erro 1 = email-ja-cadastrado
erro 2 = usuario-ja-cadastrado
erro 3 = preencha-todos-os-campos -->
<?php
session_start();
require "dbconfig/conexao.php";
if (!isset($_SESSION["idusers"])) {
    header("Location: login.php");
}
require "template/header.php";
require "template/sidebar.php";
$sql = "SELECT * FROM login;";
$stmt = $conn->prepare($sql);
$stmt->execute();
$logins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container mt-3">
    <h2 class="mb-0 mt-3 py-0">Instrutores</h2>
    <hr class="mt-0">
    <?php
    if (isset($_GET["erro"]) && $_GET["erro"] == "1") {
        echo "<div style='top: 3rem' class=''>
          <div class='alert alert-danger alert-dismissible fade show fw-semibold text-center' role='alert'>
              Preencha todos os campos!
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>
      </div>";
    }
    if (isset($_GET["nomeEspecializacao"])) {
        echo '<div style="top: 3rem" class="">
        <div class="alert alert-success alert-dismissible fade show fw-semibold text-center" role="alert">
            A especialização ' . $_GET["nomeEspecializacao"] . ' foi cadastrada  com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert"
            "" aria-label="Close"></button>
        </div>
    </div>';
    }
    if (isset($_GET["delete"]) && $_GET["delete"] == "ok") {
        echo '<div style="top: 3rem" class="">
        <div class="alert alert-warning alert-dismissible fade show fw-semibold text-center" role="alert">
            O instrutor ' . $_GET["nome-instrutor"] . ' foi deletado com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert"
            "" aria-label="Close"></button>
        </div>
    </div>';
    }
    if (isset($_GET["edit"]) && $_GET["edit"] == "ok") {
        echo '<div style="top: 3rem" class="">
        <div class="alert alert-success alert-dismissible fade show fw-semibold text-center" role="alert">
            O instrutor ' . $_GET["nome-instrutor"] . ' foi editado com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert"
            "" aria-label="Close"></button>
        </div>
    </div>';
    }

    if ($_SESSION["acesso"] == 1) {
        echo ' <button class="btn btn-primary mt-2 mb-3" data-bs-toggle="modal" data-bs-target="#modalCadastro">Cadastrar</button>';
    }
    ?>
    <button class="btn btn-primary mt-2 mb-3" data-bs-toggle="modal" data-bs-target="#modalVisualizarEspec">Ver
        Especializações</button>
    <button class="btn btn-primary mt-2 mb-3" data-bs-toggle="modal" data-bs-target="#modalEspecializacao">Cadastrar
        Especializações</button>
    <?php
    if (count($logins) > 0) { ?>
        <div class="table-responsive">
            <table class="table table-dark table-striped">
                <thead class="">
                    <tr>
                        <th>Id</th>
                        <th>Email</th>
                        <th>Usuario</th>
                        <?php
                        if ($_SESSION["acesso"] == 1) {
                            echo "<th>Ações</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($logins as $login) {
                        echo "<tr>";
                        echo "<td>" . $login["id"] . "</td>";
                        echo "<td>" . $login["email"] . "</td>";
                        echo "<td>" . $login["usuario"] . "</td>";
                        if ($_SESSION["acesso"] == 1) {
                            echo "<td><span>
              <button class='btn btn-danger btn-sm ' data-bs-toggle='modal' data-bs-target='#modalDeletar" . $login['id'] . "'>Excluir</button>
              <button class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#modalEditar" . $login['id'] . "'>Editar</button>
              </span>
          </td>";
                        }
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
    } else {
        echo '<h3 class="text-warning text-center">Ainda não há intrutores cadastrados!</h3>';

    } ?>
</div>
</div>
<!-- Modal Cadastro Instrutor-->
<div class="modal fade" id="modalCadastro" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Cadastrar Funcionário</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex justify-content-center">
                <form action="verify/cadastrar.php" method="post" class="" data-parsley-validate novalidate>
                    <span>E-mail</span>
                    <input type="email" class="form-control" name="email" id="" placeholder="Digite seu e-mail"
                        required />
                    <div>
                        <span class="text-danger mt-n1">
                            <?php
                            if (isset($_GET["erro"]) && $_GET["erro"] == "1") {
                                echo "<i class='fa-solid fa-circle-exclamation'></i> Este e-mail já está em uso.";
                            }
                            ?>
                        </span>
                    </div>
            </div>
            <div class="w-100 fw-semibold mb-3 needs-validation">
                <span>Usuário</span>
                <input type="text" class="form-control" name="usuario" id="" placeholder="Digite seu usuário"
                    minlength="3" required />
                <div>
                    <span class="corErro mt-n1 ">
                        <?php
                        if (isset($_GET["erro"]) && $_GET["erro"] == "2") {
                            echo "<i class='fa-solid fa-circle-exclamation'></i> Este nome de usuário já está em uso.";
                        }
                        ?>
                    </span>
                </div>
            </div>
            <div class="w-100 fw-semibold needs-validation">
                <span>Senha</span>
                <input type="password" class="form-control" name="senha" id="pass" placeholder="Digite sua senha"
                    minlength="8" required />
            </div>
            <div class="form-check form-check-inline mb-3">
                <input class="form-check-input" type="checkbox" onclick="showPass()" id="" />
                <label class="form-check-label text-white">Mostrar Senha</label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" name="submit" class="btn btn-primary">Cadastrar</button>
        </form>
    </div>
</div>

<!-- Modal Especialização -->
<div class="modal fade" id="modalEspecializacao" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Cadastrar Especialização</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="verify/cadEspecializacao.php" method="post" class="needs-validation" novalidate>
                    <div class="mb-3 mx-4">
                        <span class="form-label">Nome</span>
                        <input type="text" class="form-control" name="nomeEspecializacao" placeholder="Especialização "
                            required>
                        <div class="invalid-feedback">
                            Preencha este campo!
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" name="submit" class="btn btn-primary">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Deletar -->
<div class="modal fade" id="modalDeletar<?php echo $instrutor['idinstrutores']; ?>" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Excluir o instrutor <?php
                echo $instrutor['nome']
                    ?>? </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span>Está ação é irreversível!</span>
                <form method='post' action='verify/delete.php'>
                    <input type='hidden' name='id' value="<?php echo $instrutor['idinstrutores']; ?>" />
                    <input type='hidden' name='nome' value="<?php echo $instrutor['nome']; ?>" />

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Editar Instrutor -->
<div class="modal fade" id="modalEditar<?php echo $login['id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar Cadastro</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="verify/editarInstrutor.php" method="post" class="needs-validation" novalidate>
                    <input type="hidden" name="id" value="<?php echo $login['id']; ?>">
                    <div class="mb-3 mx-4">
                        <span class="form-label">Nome</span>
                        <input type="text" class="form-control" name="Usuário" value="<?php echo $login['usuario']; ?>"
                            required>
                        <div class="invalid-feedback">
                            Preencha este campo!
                        </div>
                    </div>
                    <div class="mb-3 mx-4">
                        <span class="form-label">Especialização</span>
                        <select class="form-select" name="idespecializacao" multiple
                            aria-label="Multiple select example" required>
                            <?php
                            $sql = "SELECT * FROM especializacao;";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $especializacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($especializacoes as $especializacao) {
                                if ($especializacao["id"] == $instrutor["idespecializacao"]) {
                                    echo "<option value='" . $especializacao["id"] . "' selected>" . $especializacao["nomeEspecializacao"] . "</option>";
                                } else {
                                    echo "<option value='" . $especializacao["id"] . "'>" . $especializacao["nomeEspecializacao"] . "</option>";
                                }
                            }
                            ?>
                            <div class="invalid-feedback">
                                Preencha este campo!
                            </div>
                        </select>
                    </div>
                    <div class="mb-3 mx-4">
                        <span class="form-label">Celular</span>
                        <input type="text" class="form-control celular" name="celular"
                            value="<?php echo $instrutor['celular']; ?>" maxlength="15" required>
                        <div class="invalid-feedback">
                            Preencha este campo!
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" name="submit" class="btn btn-primary">Editar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php

?>

</script>
<?php
require "template/footer.php";
require "verify/validarInput.php";
if (isset($_GET["erro"]) && $_GET["erro"] == "3") {
    echo "<div style='top: 2rem' class=' my-5 '>
            <div class='alert alert-danger alert-dismissible fade show fw-semibold text-center' role='alert'>
                Preencha todos os campos!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
        </div>";
} ?>
</div>
<?php
require "template/footer.php";
require "verify/validarInput.php";
?>