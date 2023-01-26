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
                    $oneriler = $db->prepare("SELECT * FROM oneriler");
                    $oneriler->execute();
            
                    $toplam = $oneriler->rowCount();
                ?>
                <li class="breadcrumb-item active" aria-current="page">Öneri Listesi (<?= $toplam; ?> Adet Öneri)</li>
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
                    $oneriler = $db->prepare("SELECT * FROM oneriler 
                    ORDER BY oneri_id DESC LIMIT :goster,:lim");
                    $oneriler->bindValue(":goster",(int) $goster,PDO::PARAM_INT);
                    $oneriler->bindValue(":lim",(int) $lim,PDO::PARAM_INT);
                    $oneriler->execute();

                    if($oneriler->rowCount()){

                        ?> 
                            <thead>
                                <tr>
                                    <th scpoe="col">#</th>
                                    <th scpoe="col">İsim</th>
                                    <th scpoe="col">E-Posta</th>
                                    <th scpoe="col">Link</th>
                                    <th scpoe="col">İşlemler</th>
                            </thead>

                            <tbody>
                        <?php
                        $say = 1;
                        foreach($oneriler as $row){

                            $videobilgi = mb_substr($row['oneri_link'],32,50);
                            ?> 
                                <tr>
                                    <td><?= $say; ?></td>
                                    <td><?= $row['oneri_isim']; ?></td>
                                    <td><?= $row['oneri_posta']; ?></td>
                                    <td><?= $row['oneri_link']; ?></td>
                                    <td>
                                        <a href="videolar.php?info=<?= $videobilgi; ?>"><i class="fa fa-plus text-success"></i></a> | <a href="islemler.php?islem=onerisil&id=<?= $row['oneri_id']; ?>"><i class="fa fa-trash text-danger"></i></a>
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
                                echo '<li class="page-item"<a class="page-link" href='.$site.'/admin/oneriler.php?s=1"><<</a></li>';
                                echo '<li class="page-item"<a class="page-link" href='.$site.'/admin/oneriler.php?s="'.$onceki.'">></a></li>';
                            }
                            for($i = $s - $flim;$i < $s + $flim + 1; $i++){
                                if($i>0 && $i <= $ssayi){
                                    if($i == $s){
                                        echo '<li class="page-item"><a class="page-link" style="background:#337ab7;color:#fff" href="#">'.$i.'</a></li>';
                                    }else{
                                        echo '<li class="page-item"><a class="page-link" href="'.$site.'/admin/oneriler.php?s='.$i.'">'.$i.'</a></li>';
                                    }
                                }
                            }

                            if($s <= $ssayi - 4){
                                $sonraki = $s +1;
                                echo '<li class="page-item"<a class="page-link" href='.$site.'/admin/oneriler.php?s='.$sonraki.'">></a></li>';
                                echo '<li class="page-item"<a class="page-link" href='.$site.'/admin/oneriler.php?s="'.$sayi.'">>></a></li>';
                            }
                        }
                        echo "</ul>";
                    }else{
                        echo '<div class="alert alert-danger">Öneri Yok</div>';
                    }
                    
                ?>

                
<?php require_once 'alt.php'; ?>