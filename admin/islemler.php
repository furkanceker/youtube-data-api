<?php require_once 'ust.php'; ?>
<?php require_once 'nav.php'; ?>
<div class="content-wrapper">
    <div class="container-fluid mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Panel</a></li>
                <li class="breadcrumb-item active" aria-current="page">Yapılacak İşlemler</li>
            </ol>
        </nav>

        <div class="row">

        </div>

        <div style="padding:15px;" class="card mb-3">
            <?php
                $islem = @get('islem');
                switch($islem){
                    case 'yorumsil':
                        if(isset($_SESSION['oturum'])){
                            $id = @get('id');
                            $sil = $db->prepare("DELETE FROM yorumlar WHERE yorum_id=:id");
                            $sil->execute(array(':id'=>$id));
                            if($sil){
                                echo "<div class='alert alert-success'>Silme İşlemi Başarılı</div>";
                                header('refresh:1;url=yorumlar.php');
                            }else{
                                echo "<div class='alert alert-danger'>Silme İşlemi Başarısız</div>";
                                header('refresh:1;url=yorumlar.php');
                            }
                        }
                    break;
                    case 'yoneticisil':
                        if(isset($_SESSION['oturum'])){
                            $id = @get('id');
                            if($id != $uid ){
                                $sil = $db->prepare("DELETE FROM admin WHERE id=:id");
                                $sil->execute(array(':id'=>$id));
                                if($sil){
                                    echo "<div class='alert alert-success'>Silme İşlemi Başarılı</div>";
                                    header('refresh:1;url=yoneticiler.php');
                                }else{
                                    echo "<div class='alert alert-danger'>Silme İşlemi Başarısız</div>";
                                    header('refresh:1;url=yoneticiler.php');
                                }
                            }else{
                                echo "<div class='alert alert-danger'>Kendinizi Silemezsiniz</div>";
                                header('refresh:1;url=yoneticiler.php');
                            }

                        }
                    break;
                    case 'videosil':
                        if(isset($_SESSION['oturum'])){
                            $id = @get('id');
                            $sil = $db->prepare("DELETE FROM videolar WHERE id=:id");
                            $sil->execute(array(':id'=>$id));
                            if($sil){
                                echo "<div class='alert alert-success'>Silme İşlemi Başarılı</div>";
                                header('refresh:1;url=index.php');
                            }else{
                                echo "<div class='alert alert-danger'>Silme İşlemi Başarısız</div>";
                                header('refresh:1;url=index.php');
                            }
                        }
                    break;
                }
            ?>
        </div>
                
<?php require_once 'alt.php'; ?>