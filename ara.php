<?php 
define('guvenlik',true);
require_once "ust.php"; ?>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<?php require_once "nav.php"; ?>
</nav>
<div class="container">
    <?php
        $s = @intval(get('s'));
        if(!$s){$s=1;}
        $q = @get('q');

        if(!$q){
            header('Location:'.$site.'');
        }
        $videolar = $db->prepare("SELECT * FROM videolar WHERE durum=:durum AND baslik LIKE :par");
        $videolar->execute(array(":durum"=>1,':par'=>'%'.$q.'%'));

        $toplam = $videolar->rowCount();
        $lim = 9;
        $goster = $s * $lim - $lim;
    ?>
    <h1 class="my-4"><small>Arama Sonuçları(<?= $videolar->rowCount(); ?> Adet Sonuç)</small></h1>
    <div class="row">
<?php 


$videolar = $db->prepare("SELECT * FROM videolar WHERE durum=:durum AND baslik LIKE :par ORDER BY id DESC LIMIT :goster,:lim");
$videolar->bindValue(":durum",(int) 1,PDO::PARAM_INT);
$videolar->bindValue(":goster",(int) $goster,PDO::PARAM_INT);
$videolar->bindValue(":lim",(int) $lim,PDO::PARAM_INT);
$videolar->bindValue(":par",'%'.$q.'%',PDO::PARAM_STR);
$videolar->execute();

if($videolar->rowCount()){
    foreach($videolar as $row){?>
        
        <div class="col-lg-3 col-md-4 col-sm-4 mb-3">
            <div class="card h-100">
                <a href="<?= $site;?>/detay.php?info=<?= $row["url"] ?>"><img src="<?= $row['resim'] ?>" class="card-img-top" alt="<?= $row['baslik'] ?>"></a>
                <div class="card-body">
                    <h6 class="card-title">
                        <a href="<?= $site;?>/detay.php?info=<?= $row["url"] ?>" ><?= $row['baslik'] ?></a>
                    </h6>
                    <p class="card-text"><?= mb_substr($row['aciklama'],0,80,"utf8") ?>...</p>
                </div>
            </div>
        </div>
        
        <?php }
        echo "</div>";
        echo '<ul class="pagination justify-content-center">';
        $ssayi = ceil($toplam/$lim);
        $flim = 3;
        if($ssayi < 2){
            null;
        }else{
            if($s > 4){
                $onceki = $s - 1;
                echo '<li class="page-item"<a class="page-link" href='.$site.'/ara.php?q='.$q.'&s=1"><<</a></li>';
                echo '<li class="page-item"<a class="page-link" href='.$site.'/ara.php?q='.$q.'&s="'.$onceki.'">></a></li>';
            }
            for($i = $s - $flim;$i < $s + $flim + 1; $i++){
                if($i>0 && $i <= $ssayi){
                    if($i == $s){
                        echo '<li class="page-item"><a class="page-link" style="background:#337ab7;color:#fff" href="#">'.$i.'</a></li>';
                    }else{
                        echo '<li class="page-item"><a class="page-link" href="'.$site.'/ara.php?q='.$q.'&s='.$i.'">'.$i.'</a></li>';
                    }
                }
            }

            if($s <= $ssayi - 4){
                $sonraki = $s +1;
                echo '<li class="page-item"<a class="page-link" href='.$site.'/ara.php?q='.$q.'&s='.$sonraki.'">></a></li>';
                echo '<li class="page-item"<a class="page-link" href='.$site.'/ara.php?q='.$q.'&s="'.$sayi.'">>></a></li>';
            }
        }
        echo "</ul>";
}else{
    echo "<div class='container'><div class='alert alert-danger'>Aramanızla Eşleşen Video Bulunamadı</div></div>";
}
?>
</div>
</body>

<?php require_once "alt.php"; ?>