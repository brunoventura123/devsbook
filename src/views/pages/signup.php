<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Cadastro - Devsbook</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1"/>
    <link rel="stylesheet" href="<?=$base;?>/assets/css/login.css" />
</head>
<body>
    <header>
        <div class="container">
            <a href=""><img src="<?=$base;?>/assets/images/devsbook_logo.png" /></a>
        </div>
    </header>
    <section class="container main">
        
        <form method="POST" action="<?=$base;?>/cadastro">

            <?php if(!empty($flash)): ?>
                <div style="color:red; text-align:center; margin-bottom:20px" id="flash"><?php echo $flash; ?></div>
            <?php endif; ?>

            <input placeholder="Digite seu Nome Completo" class="input" type="text" name="name" />

            <input placeholder="Digite seu e-mail" class="input" type="email" name="email" />

            <input placeholder="Digite sua Data de Nascimento" class="input" type="text" name="birthdate" id="birthdate"/>

            <input placeholder="Digite sua cidade" class="input" type="text" name="city" >

            <input placeholder="Onde você trabalha?" class="input" type="text" name="work" >

            <input placeholder="Digite sua senha" class="input" type="password" name="password" />

            <input class="button" type="submit" value="Faser Cadastro" />

            <a href="<?=$base;?>/login">Já tem conta? Faça o login</a>
        </form>
    </section>

    <script src="https://unpkg.com/imask"></script>
    <script>
        IMask(
            document.getElementById('birthdate'),
            {
                mask:'00/00/0000'
            }
        )
    </script>
</body>
</html>