<?php require_once 'ust.php'; ?>
<?php require_once 'nav.php'; ?>
<div class="content-wrapper">
    <div class="container-fluid mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Panel</a></li>
                <?php
                    $s = @intval(get('s'));
                    if(!$s){$s=1;}
                    $yoneticiler = $db->prepare("SELECT * FROM admin");
                    $yoneticiler->execute();
            
                    $toplam = $yoneticiler->rowCount();
                ?>
                <li class="breadcrumb-item active" aria-current="page">Yönetici Listesi (<?= $toplam; ?> Yönetici)</li>
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
                    $yoneticiler = $db->prepare("SELECT * FROM admin ORDER BY id DESC LIMIT :goster,:lim");
                    $yoneticiler->bindValue(":goster",(int) $goster,PDO::PARAM_INT);
                    $yoneticiler->bindValue(":lim",(int) $lim,PDO::PARAM_INT);
                    $yoneticiler->execute();

                    if($yoneticiler->rowCount()){

                        ?> 
                            <thead>
                                <tr>
                                    <th scpoe="col">#</th>
                                    <th scpoe="col">Ad Soyad</th>
                                    <th scpoe="col">E-Posta</th>
                                    <th scpoe="col">İşlemler</th>
                            </thead>

                            <tbody>
                        <?php
                        $say = 1;
                        foreach($yoneticiler as $row){
                            ?> 
                                <tr>
                                    <td><?= $say; ?></td>
                                    <td><?= $row['isim']; ?></td>
                                    <td><?= $row['posta']; ?></td>
                                    <td><a href="islemler.php?islem=yoneticiduzenle&id=<?= $row['id'] ?>"><i class="fa fa-edit"></i></a> | <a href="islemler.php?islem=yoneticisil&id=<?= $row['id'] ?>" onclick="return confirm('Silmek İstiyor Musun?');"><i class="fa fa-trash text-danger"></i></a></td>
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
                                echo '<li class="page-item"<a class="page-link" href='.$site.'/admin/yoneticiler.php?s=1"><<</a></li>';
                                echo '<li class="page-item"<a class="page-link" href='.$site.'/admin/yoneticiler.php?s="'.$onceki.'">></a></li>';
                            }
                            for($i = $s - $flim;$i < $s + $flim + 1; $i++){
                                if($i>0 && $i <= $ssayi){
                                    if($i == $s){
                                        echo '<li class="page-item"><a class="page-link" style="background:#337ab7;color:#fff" href="#">'.$i.'</a></li>';
                                    }else{
                                        echo '<li class="page-item"><a class="page-link" href="'.$site.'/admin/yoneticiler.php?s='.$i.'">'.$i.'</a></li>';
                                    }
                                }
                            }

                            if($s <= $ssayi - 4){
                                $sonraki = $s +1;
                                echo '<li class="page-item"<a class="page-link" href='.$site.'/admin/yoneticiler.php?s='.$sonraki.'">></a></li>';
                                echo '<li class="page-item"<a class="page-link" href='.$site.'/admin/yoneticiler.php?s="'.$sayi.'">>></a></li>';
                            }
                        }
                        echo "</ul>";
                    }else{
                        echo '<div class="alert alert-danger>Yönetici Yok</div>';
                    }
                    
                ?>

                
<?php require_once 'alt.php'; ?>