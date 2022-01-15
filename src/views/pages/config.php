<?=$render('header', ['loggedUser' => $loggedUser]);?>

<section class="container main">
    <?=$render('sidebar', ['activeMenu'=>'config']);?>
    <section class="feed mt-10">
        <h1 style="margin-left:20px;">Configurações</h1>
        <?php if(!empty($flash)): ?>
                <div class="flash"><?php echo $flash; ?></div>
            <?php endif; ?>
        <form class="config-form" style="margin-left:20px;" action="<?=$base;?>/config" method="POST" enctype="multipart/form-data">
            <div style="margin-top:15px;" class="label">
                <label>
                    Novo Avatar:<br/>
                    <input type="file" name="avatar"><br>
                    <img  lass="image-edit" style="max-width:150px;" src="<?=$base;?>/media/avatars/<?=$user->avatar;?>"/>
                </label>
            </div>
            <div style="margin-top:15px;" class="label" >
                <label>
                    Novo Cover:<br/>
                    <input type="file" name="cover"><br>
                    <img class="image-edit" style="max-width:150px;" src="<?=$base;?>/media/covers/<?=$user->cover;?>">
             
                </label>
            </div>
            <div style="margin-top:15px;" class="label">
                <label>
                    Nome completo:<br/>
                    <input style="font-size:13px;height:35px;width:60%;outline:0;border:0;background-color:#FFF;padding:15px;border-bottom:1px solid #ccc;" class="input" type="text" value="<?=$loggedUser->name?>" name="name">
                </label>
            </div>

            <div style="margin-top:15px;" class="label">
                <label>
                    Data de nascimento:<br/>
                    <input style="font-size:13px;height:35px;width:60%;outline:0;border:0;background-color:#FFF;padding:15px;border-bottom:1px solid #ccc;" class="input" type="text" value="<?=$loggedUser->birthdate?>" name="birthdate" id="birthdate">
                </label>
            </div>

            <div style="margin-top:15px;" class="label">
                <label>
                    E-mail:<br/>
                    <input style="font-size:13px;height:35px;width:60%;outline:0;border:0;background-color:#FFF;padding:15px;border-bottom:1px solid #ccc;" class="input" type="email" value="<?=$loggedUser->email;?>" name="email">
                </label>
            </div>

            <div style="margin-top:15px;" class="label">
                <label>
                    Cidade:<br/>
                    <input style="font-size:13px;height:35px;width:60%;outline:0;border:0;background-color:#FFF;padding:15px;border-bottom:1px solid #ccc;" class="input" type="text" value="<?=$loggedUser->city?>" name="city">
                </label>
            </div>

            <div style="margin-top:15px;" class="label">
                <label>
                    Trabalho:<br/>
                    <input style="font-size:13px;height:35px;width:60%;outline:0;border:0;background-color:#FFF;padding:15px;border-bottom:1px solid #ccc;" class="input" type="text" value="<?=$loggedUser->work?>" name="work">
                </label>
            </div>

            <hr style="margin-top:15px;">
            <div style="margin-top:15px;" class="label">
                <label>
                    Nova Senha:<br/>
                    <input style="font-size:13px;height:35px;width:60%;outline:0;border:0;background-color:#FFF;padding:15px;border-bottom:1px solid #ccc;" class="input" type="password" name="password">
                </label>
            </div>

            <div style="margin-top:15px;" class="label">
                <label>
                    Confirmar Nova Senha:<br/>
                    <input style="font-size:13px;height:35px;width:60%;outline:0;border:0;background-color:#FFF;padding:15px;border-bottom:1px solid #ccc;" class="input" type="password" name="password_confirm">
                </label>
            </div>

            <input style="margin-top:15px;" class="button" type="submit" value="Salvar" />
        </form>
    </section>
</section>

<script src="https://unpkg.com/imask" ></script>
<script>
IMask(
    document.getElementById('birthdate'),
    {
        mask:'00/00/0000'
    }
);
</script>