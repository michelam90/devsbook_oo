<?php 
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoBd.php';

// Verificando se o usuários está logado
$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'Photos';

// Verificando id do usuário passado para o perfil
$id = filter_input(INPUT_GET, 'id');
if(!$id) {
    $id = $userInfo->id;
}


// Verificando se o meu usuário está em um perfil de outro usuário
if($id !== $userInfo->id) {
    $activeMenu = ''; // Não mostrar o menu perfil ativo se estiver no perfil de outro usuário
}

$postDao = new PostDaoBd($pdo);
$userDao = new UserDaoBd($pdo);


// Pegar informações do usuário
$user = $userDao->findById($id, true);
if(!$user) {
    header("Location: ".$base);
    exit;
}

// Definindo idade 
$dateFrom = new DateTime($user->birthdate);
$dateTo = new DateTime('today');
$user->ageYears = $dateFrom->diff($dateTo)->y;


/*
$feed = $postDao->getHomeFeed($userInfo->id);
*/

require 'partials/header.php';
require 'partials/menu.php';
?>
<section class="feed">

<div class="row">
    <div class="box flex-1 border-top-flat">
        <div class="box-body">
            <div class="profile-cover" style="background-image: url('<?=$base;?>/media/covers/<?=$user->cover;?>');"></div>
            <div class="profile-info m-20 row">
                <div class="profile-info-avatar">
                    <img src="<?=$base;?>/media/avatars/<?=$user->avatar;?>" />
                </div>
                <div class="profile-info-name">
                    <div class="profile-info-name-text"><?=$user->name;?></div>
                <?php if(!empty($user->city)) { ?>
                    <div class="profile-info-location"><?=$user->city;?></div>
                <?php } ?>
                </div>
                <div class="profile-info-data row">
                    <div class="profile-info-item m-width-20">
                        <div class="profile-info-item-n"><?=count($user->followers);?></div>
                        <div class="profile-info-item-s">Seguidores</div>
                    </div>
                    <div class="profile-info-item m-width-20">
                        <div class="profile-info-item-n"><?=count($user->following);?></div>
                        <div class="profile-info-item-s">Seguindo</div>
                    </div>
                    <div class="profile-info-item m-width-20">
                        <div class="profile-info-item-n"><?=count($user->photos);?></div>
                        <div class="profile-info-item-s">Fotos</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

<div class="column">
                    
    <div class="box">
        <div class="box-body">

            <div class="full-user-photos">

                <?php foreach($user->photos as $key => $item): ?>
                    <div class="user-photo-item">
                        <a href="#modal-<?=$key;?>" data-modal-open>
                            <img src="<?=$base;?>/media/uploads/<?=$item->body;?>" />
                        </a>
                        <div id="modal-<?=$key;?>" style="display:none">
                            <img src="<?=$base;?>/media/uploads/<?=$item->body;?>" />
                        </div>
                    </div>
            
                <?php endforeach; ?>

                <?php if(count($user->photos) == 0): ?>
                    Não há fotos nesse perfil.
                <?php endif; ?>
            </div>
            
        </div>
    </div>

</div>
    
</div>

</section>

<script>
    window.onload = function() {
        var modal = new VanillaModal.default();
    }
</script>

<?php 
require 'partials/footer.php';
?>