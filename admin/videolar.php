<?php 
define("guvenlik",true);
require_once 'ust.php'; 
 
require_once 'nav.php'; 

?>
<div class="container-fluid mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Panel</a></li>
                <li class="breadcrumb-item active" aria-current="page">Yapılacak İşlemler</li>
            </ol>
        </nav>

        <div class="card mb-3" style="padding:15px;">
        <?php
            if(isset($_GET['info'])){
                $info = strip_tags(trim(get('info')));
                $apikey = $arow->apikey;

                $url = file_get_contents("https://www.googleapis.com/youtube/v3/videos?id=".$info."&key=".$apikey."&part=snippet");

                $videobilgi = json_decode($url,true);
                $sahibi = $videobilgi['items']['0']['snippet']['channelTitle'];
                $title = $videobilgi['items']['0']['snippet']['title'];
                $aciklama = $videobilgi['items']['0']['snippet']['description'];
                $img = $videobilgi['items']['0']['snippet']['thumbnails']['medium']['url'];
                $etiket = $videobilgi['items']['0']['snippet']['tags'];

                if(!$title){
                    echo "<div class='alert alert-danger'>Başlık Bulunmuyor</div>";
                }else{
                    ?>
                    <div class="embed-responsive embed-responsive-21by9">
                        <iframe src="https://www.youtube.com/embed/<?= $info; ?>" width="100%" height="360"  class="embed-responsive-item" frameborder="0"></iframe>
                    </div>
                    <?php

                }
                ?> 
                <hr>

                <?php
                    if(isset($_POST['yenivideo'])){
                        $baslik = post('baslik');
                        $sef = sef_link($baslik);
                        $sahip = post('sahip');
                        $resim = post('resim');
                        $adres = post('url');
                        $desc = $_POST['aciklama'];
                        $tag = post('etiket');
                        $tarih = date('d.m.Y h:i:s');

                        $sefyap = explode(',', $tag);
                        $dizi = array();
                        foreach($sefyap as $par){
                            $dizi[] = sef_link($par);
                        }
                        $deger = implode(',',$dizi);

                        if(!$baslik || !$sahip || !$resim || !$adres || !$desc || !$tag){
                            echo "<div class='alert alert-danger'>Boş Alanları Doldurun</div>";
                        }else{
                            $varmi = $db->prepare("SELECT * FROM videolar WHERE url=:url");
                            $varmi->execute(array(":url"=>$adres));
                            if($varmi->rowCount()){
                                echo "<div class='alert alert-danger'>Video Zaten Kayıtlı</div>";
                            }else{
                                $kaydet = $db->prepare("INSERT INTO videolar SET
                                sahibi=:s,
                                baslik=:b,
                                sef_baslik=:sb,
                                resim=:r,
                                url=:u,
                                aciklama=:a,
                                eklenmetarihi=:et,
                                goruntulenme=:g,
                                durum=:d,
                                etiketler=:e,
                                sef_etiketler=:se
                                ");
                                $kaydet->execute(array(
                                    ":s"=>$sahip,
                                    ":b"=>$baslik,
                                    ":sb"=>$sef,
                                    ":r"=>$resim,
                                    ":u"=>$adres,
                                    ":a"=>$desc,
                                    ":et"=>$tarih,
                                    ":g"=>0,
                                    ":d"=>1,
                                    ":e"=>$tag,
                                    ":se"=>$deger
                                ));
                                if($kaydet){
                                    echo "<div class='alert alert-success'>Video Ekleme Başarılı</div>";
                                    header('refresh:2;url=index.php');
                                }else{
                                    echo "<div class='alert alert-danger'>Video Kaydedilemedi</div>";
                                }
                            }
                        }
                    }
                ?>
                    <form action="" class="form-horizontal" method="post">
                        <div class="form-group mb-3">
                            <label for="baslik" class="col-lg-2 mb-1 control-label">Başlık</label>
                            <div class="col-lg-12">
                                <input type="text" value="<?= $title; ?>" name="baslik" id="baslik" placeholder="Video Başlığı" readonly class="form-control">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="sahip" class="col-lg-2 mb-1 control-label">Video Sahibi</label>
                            <div class="col-lg-12">
                                <input type="text" name="sahip" value="<?= $sahibi; ?>" id="sahip" placeholder="Video Sahibi" readonly class="form-control">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="resim" class="col-lg-2 mb-1 control-label">Video Resim</label>
                            <div class="col-lg-12">
                                <input type="text" name="resim" value="<?= $img; ?>" id="resim" placeholder="Video Resim" readonly class="form-control">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="url" class="col-lg-2 mb-1 control-label">Video URL</label>
                            <div class="col-lg-12">
                                <input type="text" name="url" id="url" value="<?= $info; ?>" placeholder="Video URL" readonly class="form-control">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="aciklama" class="col-lg-2 mb-1 control-label">Video Açıklama</label>
                            <textarea name="aciklama" id="aciklama" placeholder="Video Açıklama" class="form-control" readonly cols="30" rows="10"><?= $aciklama; ?></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="etiket" class="col-lg-2 mb-1 control-label">Video Etiket</label>
                            <div class="col-lg-12">
                                <input type="text" name="etiket" id="etiket" readonly value="<?php foreach($etiket as $row){echo $row.",";} ?>" placeholder="Video Etiket" class="form-control">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="col-lg-12">
                                <button type="submit" name="yenivideo" class="btn btn-primary">Video Ekle</button>
                            </div>
                        </div>
                    </form>
                <?php
            }else{
                header('Location:index.php');
            }
        ?>
        </div>
</div>

<?php require_once 'alt.php'; ?>