<?php 

require_once 'ust.php'; ?>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <?php require_once 'nav.php'; ?>
</nav>

<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <?php
            $veri = @get('info');
            if(!$veri){
                header('Location:'.$site.'');
            }else{
                $sec = $db->prepare("select * from videolar where url=:url and durum=:durum");
                $sec->execute(array(':url'=>$veri,':durum'=>1));
                if($sec->rowCount()){
                    $row = $sec->fetch(PDO::FETCH_OBJ);
                    $goruntulenme = @$_COOKIE[$row->id];
                    if(!isset($goruntulenme)){
                        $okunmasayisi = $db->prepare("UPDATE videolar SET goruntulenme=:g WHERE url=:url");
                        $okunmasayisi->execute(array(':g'=>$row->goruntulenme+1,':url'=>$veri));
                        setcookie($row->id,'1',time()+3600);
                    }
                    ?>
                    <h1 class="mt-4"><?= $row->baslik; ?></h1>
                    <p class="head">by <a href="#"><?= $row->sahibi; ?></a></p>
                    <hr>
                    <p><i class="fa fa-calendar" aria-hidden="true"></i> <?= $row->eklenmetarihi; ?> | <i class="fa fa-eye" aria-hidden="true"></i> <?= $row->goruntulenme; ?> </p>
                    <hr>
                    <div class="card my-4">
                        <div class="card-body">
                            <div class="embed-responsive embed-responsive-21by9">
                                <iframe src="https://www.youtube.com/embed/<?= $row->url; ?>" frameborder="0" width="900" height="400"></iframe>
                            </div>
                            <hr>
                            <p class="m-5"><?= nl2br($row->aciklama); ?></p>
                        </div>
                    </div>
                    <?php
                }else{
                    header('Location:'.$site.'');
                }
            }

            ?>
            
            <hr>
            <div class="card my-4">
                <h5 class="card-header">Yorum Yap (0 Adet Yorum)</h5>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" name="isim" placeholder="Ad Soyad">
                        </div>
                        <div class="form-group">
                            <input type="mail" class="form-control" name="mail" placeholder="E-Posta">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card my-4">
                <h5 class="card-header">Arama Yap</h5>
                <div class="card-body">
                    <div class="input-group">
                        <form action="" method="get">
                            <input type="text" class="form-control" style="width:100%" name="q" placeholder="Video Ara">
                            <span class="input-group-btn"><button class="btn btn-success" type="button">Ara</button></span>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card my-4">
                <h4 class="card-header">Duyurular</h4>
                <div class="card-body"><?= $arow->duyuru; ?></div>
            </div>
        </div>
    </div>
</div>