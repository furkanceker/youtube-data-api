<?php 
define("guvenlik",true);
require_once 'ust.php'; 
 
require_once 'nav.php'; 
?>
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
                    case 'onerisil':
                        if(isset($_SESSION['oturum'])){
                            $id = @get('id');
                            $sil = $db->prepare("DELETE FROM oneriler WHERE oneri_id=:id");
                            $sil->execute(array(':id'=>$id));
                            if($sil){
                                echo "<div class='alert alert-success'>Silme İşlemi Başarılı</div>";
                                header('refresh:1;url=oneriler.php');
                            }else{
                                echo "<div class='alert alert-danger'>Silme İşlemi Başarısız</div>";
                                header('refresh:1;url=oneriler.php');
                            }
                        }
                    break;
                    case 'onaykaldir':
                        if(isset($_SESSION['oturum'])){
                            $id = @get('id');
                            $update = $db->prepare("UPDATE yorumlar SET yorum_durum=:durum WHERE yorum_id=:id");
                            $update->execute(array(':durum'=>2,':id'=>$id));
                            if($update){
                                echo "<div class='alert alert-success'>Onay Kaldırıldı</div>";
                                header('refresh:1;url=yorumlar.php');
                            }else{
                                echo "<div class='alert alert-danger'>Onay Kaldırma Başarısız</div>";
                                header('refresh:1;url=yorumlar.php');
                            }
                        }
                    break;
                    case 'yorumonayla':
                        if(isset($_SESSION['oturum'])){
                            $id = @get('id');
                            $update = $db->prepare("UPDATE yorumlar SET yorum_durum=:durum WHERE yorum_id=:id");
                            $update->execute(array(':durum'=>1,':id'=>$id));
                            if($update){
                                echo "<div class='alert alert-success'>Onaylama Başarılı</div>";
                                header('refresh:1;url=yorumlar.php');
                            }else{
                                echo "<div class='alert alert-danger'>Onaylama Başarısız</div>";
                                header('refresh:1;url=yorumlar.php');
                            }
                        }
                    break;
                    case 'yoneticiduzenle':
                        if(isset($_SESSION['oturum'])){
                            $id = @get('id');
                            $sec = $db->prepare("SELECT * FROM admin WHERE id=:id");
                            $sec->execute(array(':id'=>$id));
                            if($sec->rowCount()){
                                $row = $sec->fetch(PDO::FETCH_OBJ);

                                if(isset($_POST['yoneticiguncelle'])){
                                    $eposta = post('eposta');
                                    $isim = post('adsoyad');
                                    $sifre = post('sifre');
                                    $sifreli = sha1(md5($sifre));

                                    if(!$eposta || !$isim){
                                        echo "<div class='alert alert-danger'>İsim ve E-Posta Boş Bırakılamaz</div>";
                                    }else{
                                        $varmi = $db->prepare("SELECT * FROM admin WHERE posta=:posta AND id!=:id");
                                        $varmi->execute(array(":posta"=>$eposta,":id"=>$id));
                                        if($varmi->rowCount()){
                                            echo "<div class='alert alert-danger'>Bu Yönetici Zaten Kayıtlı</div>";
                                        }else{
                                            if(!filter_var($eposta,FILTER_VALIDATE_EMAIL)){
                                                echo "<div class='alert alert-danger'>Geçersiz E-Posta Adresi</div>";
                                            }else{
                                                if(@$_POST['sifre']==""){
                                                    $guncelle = $db->prepare("UPDATE admin SET posta=:p,isim=:i WHERE id=:id");
                                                    $guncelle->execute(array(":p"=>$eposta,":i"=>$isim,":id"=>$id));
                                                    if($guncelle){
                                                        echo "<div class='alert alert-success'>Yönetici Ayarları Güncellendi</div>";
                                                        header("refresh:2;url=yoneticiler.php");
                                                    }else{
                                                        echo "<div class='alert alert-danger'>Hata Oluştu</div>";
                                                    }
                                                }else{
                                                    $guncelle = $db->prepare("UPDATE admin SET posta=:p,isim=:i,sifre=:s WHERE id=:id");
                                                    $guncelle->execute(array(":p"=>$eposta,":i"=>$isim,":id"=>$id,":s"=>$sifreli));
                                                    if($guncelle){
                                                        echo "<div class='alert alert-success'>Yönetici Ayarları Güncellendi</div>";
                                                        header("refresh:2;url=yoneticiler.php");
                                                    }else{
                                                        echo "<div class='alert alert-danger'>Hata Oluştu</div>";
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                ?>
                                    <form action="" method="POST" class="form-horizontal">
                                        <div class="form-group mb-3">
                                            <label for="inputEmail" class="col-lg-2 control-label">Yönetici E-Posta</label>
                                            <div class="col-lg-4">
                                                <input type="email" value="<?= $row->posta; ?>" name="eposta" id="inputEmail" class="form-control" placeholder="E-Posta">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="inputEmail2" class="col-lg-2 control-label">Yönetici Adı</label>
                                            <div class="col-lg-4">
                                                <input type="text" value="<?= $row->isim; ?>" name="adsoyad" id="inputEmail2" class="form-control" placeholder="Ad Soyad">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="inputEmail3" class="col-lg-2 control-label">Yönetici Şifre</label>
                                            <div class="col-lg-4">
                                                <div class="text-danger">Değiştirmek İstemiyorsanız Boş Bırakabilirsiniz</div>
                                                <input type="password" name="sifre" id="inputEmail3" class="form-control" placeholder="Şifre">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <div class="col-lg-4">
                                                <button type="submit" name="yoneticiguncelle" class="btn btn-success">Güncelle</button>
                                            </div>
                                        </div>
                                    </form>
                                <?php
                            }else{
                                header('Location:yoneticiler.php');
                            }
                        }
                    break;
                    case 'videoduzenle':
                        if(isset($_SESSION['oturum'])){
                            $id = @get('id');
                            $sec = $db->prepare("SELECT * FROM videolar WHERE id=:id");
                            $sec->execute(array(':id'=>$id));
                            if($sec->rowCount()){
                                $row = $sec->fetch(PDO::FETCH_OBJ);

                                if(isset($_POST['videoguncelle'])){
                                    $baslik = post('baslik');
                                    $sef = sef_link($baslik);
                                    $sahip = post('sahip');
                                    $resim = post('resim');
                                    $url = post('url');
                                    $aciklama = $_POST['aciklama'];
                                    $etiket = post('etiket');
                                    $durum = post('durum');
                                    $sefyap = explode(',',$etiket);
                                    $dizi = array();
                                    foreach($sefyap as $par){
                                        $dizi[] = sef_link($par);
                                    }
                                    $deger = implode(',',$dizi);

                                    if(!$baslik || !$sahip || !$resim || !$url || !$aciklama || !$etiket){
                                        echo '<div class="alert alert-danger">Boş Alanları Doldurun</div>';
                                    }else{
                                        $varmi = $db->prepare("SELECT * FROM videolar WHERE url=:url AND id !=:id");
                                        $varmi->execute(array(":url"=>$url,":id"=>$id));
                                        if($varmi->rowCount()){
                                            echo '<div class="alert alert-danger">Video Zaten Var</div>';
                                        }else{
                                            $guncelle = $db->prepare("UPDATE videolar SET 
                                            sahibi=:s,
                                            baslik=:b,
                                            sef_baslik=:sb,
                                            resim=:r,
                                            url=:u,
                                            aciklama=:a,
                                            durum=:d,
                                            etiketler=:e,
                                            sef_etiketler=:se WHERE id=:id
                                            ");
                                            $guncelle->execute(array(
                                                ":s"=>$sahip,
                                                ":b"=>$baslik,
                                                ":sb"=>$sef,
                                                ":r"=>$resim,
                                                ":u"=>$url,
                                                ":a"=>$aciklama,
                                                ":d"=>$durum,
                                                ":e"=>$etiket,
                                                ":se"=>$deger,
                                                ":id"=>$id
                                            ));
                                            if($guncelle){
                                                echo '<div class="alert alert-success">Video Güncellendi</div>';
                                                header('refresh:3;url=index.php');
                                            }else{
                                                echo '<div class="alert alert-danger">Video Güncellenemedi</div>';
                                            }
                                        }
                                    }
                                }
                                ?>
                                    <form action="" method="POST" class="form-horizontal">
                                        <div class="form-group mb-3">
                                            <label for="inputEmail" class="col-lg-2 control-label">Video Başlık</label>
                                            <div class="col-lg-4">
                                                <input type="text" value="<?= $row->baslik; ?>" name="baslik" id="inputEmail" class="form-control" placeholder="Video Başlığı">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="inputEmail2" class="col-lg-2 control-label">Video Sahibi</label>
                                            <div class="col-lg-4">
                                                <input type="text" value="<?= $row->sahibi; ?>" name="sahip" id="inputEmail2" class="form-control" placeholder="Video Sahibi">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="inputEmail3" class="col-lg-2 control-label">Video Resim</label>
                                            <div class="col-lg-4">
                                                <input type="text" value="<?= $row->resim; ?>" name="resim" id="inputEmail3" class="form-control" placeholder="Video Resim URL">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="inputEmail4" class="col-lg-2 control-label">Video URL</label>
                                            <div class="col-lg-4">
                                                <input type="text" value="<?= $row->url; ?>" name="url" id="inputEmail4" class="form-control" placeholder="Video URL">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="inputEmail5" class="col-lg-2 control-label">Video Açıklama</label>
                                            <div class="col-lg-4">
                                                <textarea rows="10" col="30" placeholder="Video Açıklaması" name="aciklama" id="inputEmail5" class="form-control"><?= $row->aciklama; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="inputEmail6" class="col-lg-2 control-label">Video Etiketler</label>
                                            <div class="col-lg-4">
                                                <input type="text" value="<?= $row->etiketler; ?>" name="etiket" id="inputEmail6" class="form-control" placeholder="Video Etiketleri">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="inputEmail6" class="col-lg-2 control-label">Video Durum</label>
                                            <div class="col-lg-4">
                                                <select name="durum" class="form-control">
                                                    <?php
                                                        if($row->durum == 1){
                                                            echo '<option value="1" selected>Onaylı</option>
                                                                <option value="2">Beklemede</option>';
                                                        }else{
                                                            echo '<option value="1" >Onaylı</option>
                                                            <option value="2" selected>Beklemede</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <div class="col-lg-4">
                                                <button type="submit" name="videoguncelle" class="btn btn-success">Video Güncelle</button>
                                            </div>
                                        </div>
                                    </form>
                                <?php
                            }else{
                                header("Location:index.php");
                            }
                        }
                    break;
                }
            ?>
        </div>
                
<?php require_once 'alt.php'; ?>