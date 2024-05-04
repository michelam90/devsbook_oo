<?php 
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoBd.php';
require_once 'dao/UserRelationDaoBd.php';

// Verificando se o usuários está logado
$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'profile';

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
$userRelationDao = new UserRelationDaoBd($pdo);


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

// Pegar feed do usuário
$feed = $postDao->getUserFeed($id);


// Verificar se eu SIGO o usuário
$isFollowing = $userRelationDao->isFollowing($userInfo->id, $id);

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
                    <?php if($id != $userInfo->id): ?>
                        <div class="profile-info-item m-width-20">
                            <a href="follow_action.php?id=<?=$id;?>" class="button"><?=(!$isFollowing) ? 'Seguir' : 'Deixar de seguir'; ?></a>
                        </div>
                    <?php endif; ?>
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

    <div class="column side pr-5">
        
        <div class="box">
            <div class="box-body">
                
                <div class="user-info-mini">
                    <img src="assets/images/calendar.png" />
                    <?= date('d/m/Y', strtotime($user->birthdate)); ?> (<?=$user->ageYears;?> anos)
                </div>
                
                <?php if(!empty($user->city)) { ?>
                <div class="user-info-mini">
                    <img src="<?=$base;?>/assets/images/pin.png" />
                    <?=$user->city;?>
                </div>
                <?php } ?>
                
                <?php if(!empty($user->work)) { ?>
                <div class="user-info-mini">
                    <img src="<?=$base;?>/assets/images/work.png" />
                    <?=$user->work;?>
                </div>
                <?php } ?>

            </div>
        </div>

        <div class="box">
            <div class="box-header m-10">
                <div class="box-header-text">
                    Seguindo
                    <span>(<?=count($user->following);?>)</span>
                </div>
                <div class="box-header-buttons">
                    <a href="<?=$base;?>/amigos.php?id=<?=$user->id;?>">ver todos</a>
                </div>
            </div>
            <div class="box-body friend-list">
                <?php if(count($user->following) > 0) { ?>

                    <?php foreach($user->following as $item) { ?>
                    
                    <div class="friend-icon">
                        <a href="<?=$base;?>/perfil.php?id=<?=$item->id;?>">
                            <div class="friend-icon-avatar">
                                <img src="<?=$base;?>/media/avatars/<?=$item->avatar;?>" />
                            </div>
                            <div class="friend-icon-name">
                                <?=$item->name;?>
                            </div>
                        </a>
                    </div>
                <?php } // End foreache ?>
            <?php } // end if?>
                
            </div>
        </div>

    </div>
    <div class="column pl-5">

        <div class="box">
            <div class="box-header m-10">
                <div class="box-header-text">
                    Fotos
                    <span>(<?=count($user->photos);?>)</span>
                </div>
                <div class="box-header-buttons">
                    <a href="<?=$base;?>/fotos.php?id=<?=$user->id;?>">ver todos</a>
                </div>
            </div>
            <div class="box-body row m-20">
                
                <?php if(count($user->photos) > 0): ?>

                    <?php foreach($user->photos as $key => $item): ?>

                        <div class="user-photo-item">
                            <a href="#modal-<?=$key;?>" rel="modal:open">
                                <img src="<?=$base;?>/media/uploads/<?=$item->body;?>" />
                            </a>
                            <div id="modal-<?=$key;?>" style="display:none">
                                <img src="<?=$base;?>/media/uploads/<?=$item->body;?>" />
                            </div>
                        </div>

                    <?php endforeach; ?>
                
                <?php endif; ?>
                
            </div>
        </div>
        
        <?php if($id == $userInfo->id): // Se estiver no meu perfil, mostrar o editor de feed, no se outro usuário não mostrar ?>
            <?php require 'partials/feed-editor.php'; ?>
        <?php endif; ?>

        <?php if(count($feed) > 0): ?>
            <?php foreach($feed as $item): ?>
                <?php require 'partials/feed-item.php';?>
            <?php endforeach; ?>
        <?php else: ?>
            Não há postagens desse usuário.
        <?php endif; ?>
    </div>
    
</div>

</section>

<?php 
require 'partials/footer.php';
?>