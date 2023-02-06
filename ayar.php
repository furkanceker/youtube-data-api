<?php

@session_start();
@ob_start();

try {
    $db = new PDO("mysql:host=localhost;dbname=videoscript;charset=utf8;","root","");
    $db->query("SET CHARACTER SET UTF8");
    $db->query("SET NAMES UTF8");
} catch (PDOException $hata) {
    print_r($hata->getMessage());
}

$ayarlar = $db->prepare("SELECT * FROM ayarlar");
$ayarlar->execute();
$arow = $ayarlar->fetch(PDO::FETCH_OBJ);

$site = $arow->url;

if(isset($_SESSION['oturum'])){
    $oturum = $db->prepare("SELECT * FROM admin where id=:id");
    $oturum->execute(array(':id'=>@$_SESSION['id']));
    if($oturum->rowCount()){
        $row = $oturum->fetch(PDO::FETCH_OBJ);
        $uid = $row->id;
        $uisim = $row->isim;
    }
}
?>