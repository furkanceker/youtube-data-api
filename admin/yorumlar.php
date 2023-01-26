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
                <?php
                    $s = @intval(get('s'));
                    if(!$s){$s=1;}
                    $yorumlar = $db->prepare("SELECT * FROM yorumlar INNER JOIN videolar ON videolar.id = yorumlar.yorum_video_id");
                    $yorumlar->execute();
            
                    $toplam = $yorumlar->rowCount();
                ?>
                <li class="breadcrumb-item active" aria-current="page">Yorumlar (<?= $toplam; ?> Adet Yorum)</li>
            </ol>
        </nav>

        <div class="row">

        </div>
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover">
                <?php

                    $lim = 5;
                    $goster = $s * $lim - $lim;
                    $yorumlar = $db->prepare("SELECT * FROM yorumlar 
                    INNER JOIN videolar ON videolar.id = yorumlar.yorum_video_id 
                    ORDER BY yorum_id DESC LIMIT :goster,:lim");
                    $yorumlar->bindValue(":goster",(int) $goster,PDO::PARAM_INT);
                    $yorumlar->bindValue(":lim",(int) $lim,PDO::PARAM_INT);
                    $yorumlar->execute();

                    if($yorumlar->rowCount()){

                        ?> 
                            <thead>
                                <tr>
                                    <th scpoe="col">#</th>
                                    <th scpoe="col">Video</th>
                                    <th scpoe="col">İsim</th>
                                    <th scpoe="col">Web Site</th>
                                    <th scpoe="col">E-Posta</th>
                                    <th scpoe="col">Yorum</th>
                                    <th scpoe="col">İşlemler</th>
                            </thead>

                            <tbody>
                        <?php
                        $say = 1;
                        foreach($yorumlar as $row){
                            ?> 
                                <tr>
                                    <td><?= $say; ?></td>
                                    <td><a target="_blank" href="<?= $site.'/detay.php?info='.$row['url']?>"><?= $row['baslik']; ?></a></td>
                                    <td><?= $row['yorum_isim']; ?></td>
                                    <td><?= $row['yorum_website']; ?></td>
                                    <td><?= $row['yorum_posta']; ?></td>
                                    <td><?= $row['yorum_icerik']; ?></td>
                                    <td>
                                        <?php if($row['yorum_durum']==1){
                                            echo '<div style="color:green;font-weight:bold"><i class="fa fa-check" aria-hidden="true"></i> Onaylı</div>';
                                        }else {

                                            echo '<div style="color:red;font-weight:bold"><i class="fa fa-times" aria-hidden="true"></i> Onaysız</div>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if($row['yorum_durum']==1){ 
                                            ?>
                                            <a href="islemler.php?islem=onaykaldir&id=<?= $row["yorum_id"] ?>"><i class="fa fa-eraser"></i></a>
                                            <?php
                                        }else{
                                            ?>
                                            <a href="islemler.php?islem=yorumonayla&id=<?= $row["yorum_id"] ?>"><i class="fa fa-check"></i></a>
                                            <?php
                                        }
                                         ?> | 
                                     <a href="islemler.php?islem=yorumsil&id=<?= $row['yorum_id'] ?>" onclick="return confirm('Silmek İstiyor Musun?');"><i class="fa fa-trash text-danger"></i></a>
                                     </td>
                                </tr>
                                <?php
                            $say++;
                        }
                        
                        echo '</tbody></table>
                        </div>
                        </div>';
                        echo '<ul class="pagination">';
                        $ssayi = ceil($toplam/$lim);
                        $flim = 3;
                        if($ssayi < 2){
                            null;
                        }else{
                            if($s > 4){
                                $onceki = $s - 1;
                                echo '<li class="page-item"<a class="page-link" href='.$site.'/admin/yorumlar.php?s=1"><<</a></li>';
                                echo '<li class="page-item"<a class="page-link" href='.$site.'/admin/yorumlar.php?s="'.$onceki.'">></a></li>';
                            }
                            for($i = $s - $flim;$i < $s + $flim + 1; $i++){
                                if($i>0 && $i <= $ssayi){
                                    if($i == $s){
                                        echo '<li class="page-item"><a class="page-link" style="background:#337ab7;color:#fff" href="#">'.$i.'</a></li>';
                                    }else{
                                        echo '<li class="page-item"><a class="page-link" href="'.$site.'/admin/yorumlar.php?s='.$i.'">'.$i.'</a></li>';
                                    }
                                }
                            }

                            if($s <= $ssayi - 4){
                                $sonraki = $s +1;
                                echo '<li class="page-item"<a class="page-link" href='.$site.'/admin/yorumlar.php?s='.$sonraki.'">></a></li>';
                                echo '<li class="page-item"<a class="page-link" href='.$site.'/admin/yorumlar.php?s="'.$sayi.'">>></a></li>';
                            }
                        }
                        echo "</ul>";
                    }else{
                        echo '<div class="alert alert-danger">Yorum Yok</div>';
                    }
                    
                ?>

                
<?php require_once 'alt.php'; ?>