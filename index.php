<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoBd.php';

// Verificando se o usuários está logado
$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$activeMenu = 'Home';

// Pegando informações para fazer a paginação do feed
$page = intval(filter_input(INPUT_GET, 'p'));

if($page < 1) {
    $page = 1;
}

// Pegando informações para montar o feed
$postDao = new PostDaoBd($pdo);
$info = $postDao->getHomeFeed($userInfo->id, $page);
$feed = $info['feed'];
$pages = $info['pages'];
$correntPage = $info['correntPage'];

/*
echo "<pre>";
print_r($feed);
exit;
*/

require 'partials/header.php';
require 'partials/menu.php';
?>

<section class="feed mt-10">
<?php //print_r($userInfo); ?>
    <div class="row">
        <div class="column pr-5">
            <?php require 'partials/feed-editor.php'; ?>

            <?php // Listando o feed
                foreach($feed as $item) {
                    require 'partials/feed-item.php'; 
                }
            ?>

            <div class="feed-pagination">
                <?php for($i=0; $i < $pages; $i++): ?>
                        <a class="<?=($i+1==$correntPage) ? 'active' : '';?>" href="<?=$base;?>?p=<?=$i+1;?>"> <?=$i+1;?> </a>
                
                <?php endfor; ?>
            </div>
            
        </div>

        
        
        <div class="column side pl-5">
            <div class="box banners">
                <div class="box-header">
                    <div class="box-header-text">Patrocinios</div>
                    <div class="box-header-buttons">
                        
                    </div>
                </div>
                <div class="box-body">
                    <a href=""><img src="<?=$base;?>/assets/images/php.jpg" /></a>
                    <a href=""><img src="<?=$base;?>/assets/images/laravel.jpg" /></a>
                </div>
            </div>
                <div class="box">
                    <div class="box-body m-10">
                        Criado por Michel
                    </div>
                </div>
            </div>
    </div>
</section>


<?php 
require 'partials/footer.php';
?>