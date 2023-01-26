<?php 
define('guvenlik',true);
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
            $yorumlar = $db->prepare("SELECT * FROM yorumlar WHERE yorum_video_id=:id AND yorum_durum=:durum");
            $yorumlar->execute(array(':id'=>$row->id,':durum'=>1));
            ?>
            
            <hr>
            <div class="card my-4">
                <h5 class="card-header">Yorum Yap (<?= $yorumlar->rowCount(); ?> Adet Yorum)</h5>
                <div class="card-body">

                <?php
                    if(isset($_POST['gonder'])){
                        $isim = post('isim');
                        $eposta = post('mail');
                        $website = post('website');
                        $yorum = post('yorum');

                        if(!$isim || !$eposta || !$yorum){
                            echo "<div class='alert alert-danger'>Lütfen Boş Alanları Doldurun</div>";
                        }else{
                            if(!filter_var($eposta,FILTER_VALIDATE_EMAIL)){
                                echo "<div class='alert alert-danger'>Hatalı E-Posta Adresi</div>";
                            }else{
                                $yorumekle = $db->prepare("INSERT INTO yorumlar SET 
                                    yorum_video_id=:video,
                                    yorum_isim=:isim,
                                    yorum_posta=:posta,
                                    yorum_website=:website,
                                    yorum_icerik=:icerik,
                                    yorum_durum=:durum
                                ");
                                $yorumekle->execute(array(
                                    ':video'=>$row->id,
                                    ':isim'=>$isim,
                                    ':posta'=>$eposta,
                                    ':website'=>$website,
                                    ':icerik'=>$yorum,
                                    ':durum'=>2
                                ));
                                if($yorumekle){
                                    echo "<div class='alert alert-success'>Yorumunuz Onaylanmak Üzere Gönderildi</div>";
                                    header("refresh:3;url=".@$_SERVER['HTTP_REFERER']);
                                }else{
                                    echo "<div class='alert alert-danger'>Hata Oluştu</div>";
                                }
                            }
                        }
                    }

                ?>
                    <form action="" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" name="isim" placeholder="Ad Soyad">
                        </div>
                        <div class="form-group">
                            <input type="mail" class="form-control" name="mail" placeholder="E-Posta">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="website" placeholder="Web Site Adresi">
                        </div>
                        <div class="form-group">
                            <textarea name="yorum" class="form-control"  cols="30" rows="3" placeholder="Yorum Yazın"></textarea>
                        </div>
                        <button type="submit" name="gonder" class="btn btn-success">Gönder</button>
                    </form>
                </div>
            </div>
            <?php 
                if($yorumlar->rowCount()){
                    foreach($yorumlar as $yorum){
                        ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="mt-0"><a href="<?= $yorum['yorum_website']; ?>" target="_blank"><?= $yorum['yorum_isim']; ?></a></h5>
                                <?= $yorum['yorum_icerik']; ?>
                            </div>
                        </div>
                        <?php
                    }
                }else{
                    echo "<div class='alert alert-danger'>Henüz Yorum Yok</div>";
                }
            ?>
        </div>
        <div class="col-lg-4">
            <div class="card my-4">
                <h5 class="card-header">Arama Yap</h5>
                <div class="card-body">
                    <div class="input-group">
                        <form action="ara.php" method="get">
                            <input type="text" class="form-control" style="width:100%" name="q" placeholder="Video Ara">
                            <span class="input-group-btn"><button class="btn btn-success" type="submit">Ara</button></span>
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