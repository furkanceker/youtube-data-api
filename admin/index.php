<?php require_once 'ust.php'; ?>
<?php require_once 'nav.php'; 
$s = @intval(get('s'));
if(!$s){$s=1;}
$videolar = $db->prepare("SELECT * FROM videolar");
$videolar->execute();
            
$toplam = $videolar->rowCount();
$lim = 5;
$goster = $s * $lim - $lim;
?>
<div class="content-wrapper">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Panel</a></li>
                <li class="breadcrumb-item active" aria-current="page">Video Listesi (<?= $toplam ?>)</li>
            </ol>
        </nav>
        <div class="row">

        </div>
        <div class="card mb-3">
            <div class="table-responsive">
                <table class="table table-hover">
                    <?php
                    $s = @intval(get('s'));
                    if(!$s){$s=1;}
                    $videolar = $db->prepare("SELECT * FROM videolar");
                    $videolar->execute();
            
                    $toplam = $videolar->rowCount();
                    $lim = 5;
                    $goster = $s * $lim - $lim;
                    $videolar = $db->prepare("SELECT * FROM videolar ORDER BY id DESC LIMIT :goster,:lim");
                    $videolar->bindValue(":goster",(int) $goster,PDO::PARAM_INT);
                    $videolar->bindValue(":lim",(int) $lim,PDO::PARAM_INT);
                    $videolar->execute();

                    if($videolar->rowCount()){

                        ?>
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Video Resim</th>
                                <th scope="col">Video Başlık</th>
                                <th scope="col">Video Url</th>
                                <th scope="col">Video Sahibi</th>
                                <th scope="col">Durum</th>
                                <th scope="col">İşlemler</th>
                            </tr>
                        </thead>
                        <?php
                        foreach($videolar as $row){
                            ?>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td><img src="<?= $row['resim']; ?>" width="100" height="70"></td>
                                    <td><a href="<?= $site.'/detay.php?info='.$row['url']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Sitede İzle"><?= $row['baslik']; ?></a></td>
                                    <td><a href="https://www.youtube.com/watch?v=<?= $row['url']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Youtube'da İzle"><?= $row['url']; ?></a></td>
                                    <td><?= $row['sahibi']; ?></td>
                                    <td>
                                        <?php if($row['durum']==1){
                                            echo '<div style="color:green;font-weight:bold"><i class="fa fa-check" aria-hidden="true"></i> Onaylı</div>';
                                        }else {

                                            echo '<div style="color:red;font-weight:bold"><i class="fa fa-times" aria-hidden="true"></i> Onaysız</div>';
                                        }
                                        ?>
                                    </td>
                                    <td><a href="islemler.php?islem=videoduzenle&id=<?= $row['id'];?>"><i class="fa fa-edit"></i></a> | <a href="islemler.php?islem=videosil&id=<?= $row['id']; ?>" onclick="return confirm('Silmek İstiyor Musunuz?');"><i class="fa fa-trash text-danger"></i></a></td>
                                </tr>
                            </tbody>
                            <?php
                        }
                        echo '<ul">';
                        $ssayi = ceil($toplam/$lim);
                        $flim = 3;
                        if($ssayi < 2){
                            null;
                        }else{
                            if($s > 4){
                                $onceki = $s - 1;
                                echo '<li class="page-item"<a class="page-link" href='.$site.'/admin/index.php?s=1"><<</a></li>';
                                echo '<li class="page-item"<a class="page-link" href='.$site.'/admin/index.php?s="'.$onceki.'">></a></li>';
                            }
                            for($i = $s - $flim;$i < $s + $flim + 1; $i++){
                                if($i>0 && $i <= $ssayi){
                                    if($i == $s){
                                        echo '<li class="page-item"><a class="page-link" style="background:#337ab7;color:#fff" href="#">'.$i.'</a></li>';
                                    }else{
                                        echo '<li class="page-item"><a class="page-link" href="'.$site.'/admin/index.php?s='.$i.'">'.$i.'</a></li>';
                                    }
                                }
                            }

                            if($s <= $ssayi - 4){
                                $sonraki = $s +1;
                                echo '<li class="page-item"<a class="page-link" href='.$site.'/admin/index.php?s='.$sonraki.'">></a></li>';
                                echo '<li class="page-item"<a class="page-link" href='.$site.'/admin/index.php?s="'.$sayi.'">>></a></li>';
                            }
                        }
                        echo "</ul>";
                    }else{
                        echo '<div class="alert alert-danger>Henüz Video Yok</div>"';
                    }

                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
                
<?php require_once 'alt.php'; ?>
