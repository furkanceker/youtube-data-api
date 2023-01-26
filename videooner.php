<?php 
define('guvenlik',true);
require_once 'ust.php'; ?>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<?php require_once "nav.php"; ?>
</nav>
<div class="container">
    <div class="row">
    <div class="col-lg-8">
        <div class="card my-4">
            <h5 class="card-header">Video Önerisi</h5>
            <div class="card-body">

            <?php
                if(isset($_POST['istekgonder'])){
                    $isim = post('isim');
                    $eposta = post('eposta');
                    $link = post('link');

                    if(!$isim || !$eposta || !$link){
                        echo '<div class="alert alert-danger">Boş Alanları Doldurunuz</div>';
                    }else{
                        if(!filter_var($eposta,FILTER_VALIDATE_EMAIL)){
                            echo '<div class="alert alert-danger">Geçersiz E-Posta</div>';
                        }else{
                            $oner = $db->prepare("INSERT INTO oneriler SET
                                oneri_isim=:isim,
                                oneri_posta=:posta,
                                oneri_link=:link
                            ");
                            $oner->execute(array(":isim"=>$isim,":posta"=>$eposta,":link"=>$link));
                            if($oner){
                                echo '<div class="alert alert-success">Öneriniz Gönderildi</div>';
                                header("refresh:2;url=".$site."");
                            }else{
                                echo '<div class="alert alert-danger">Öneri Başarısız</div>';
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
                        <input type="text" class="form-control" name="eposta" placeholder="E-Posta">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="link" placeholder="Video Linki (Youtube)">
                    </div>
                    <button type="submit" name="istekgonder" class="btn btn-primary">İstek Gönder</button>
                </form>
            </div>
        </div>
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
<?php require_once 'alt.php'; ?>