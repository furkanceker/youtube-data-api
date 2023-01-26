<?php
require_once '../sistem/fonksiyon.php'; 
if(isset($_SESSION['oturum'])){
    header('Location:index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Paneli | Giriş</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body class="bg-dark">
        <div class="container col-3">
            <div class="card card-login mx-auto mt-5">
                <div class="card-header">Yönetim Paneli</div>
                <div class="card-body">
                    <?php
                        if(isset($_POST['girisyap'])){
                            $eposta = post('eposta');
                            $sifre = post('sifre');
                            $sifreli = sha1(md5($sifre));
                            if(!$eposta || !$sifre){
                                echo "<div class='alert alert-danger'>Boş Alanları Doldurun</div>";
                            }else{
                                if(!filter_var($eposta,FILTER_VALIDATE_EMAIL)){
                                    echo "<div class='alert alert-danger'>Geçersiz E-Posta</div>";
                                }else{
                                    $giris = $db->prepare("SELECT * FROM admin WHERE posta=:p AND sifre=:s");
                                    $giris->execute(array(":p"=>$eposta,":s"=>$sifreli));
                                    if($giris->rowCount()){
                                        $row = $giris->fetch(PDO::FETCH_OBJ);
                                        @$_SESSION['oturum'] = true;
                                        @$_SESSION['id'] = $row->id;
                                        echo "<div class='alert alert-success'>Giriş Başarılı</div>";
                                        header('Refresh:3;url=index.php');
                                    }else{
                                        echo "<div class='alert alert-danger'>Giriş Başarısız</div>";
                                    }
                                }
                            }
                        }
                    ?>
                    <form action="" method="post">
                    <div class="form-group">
                        <label for="exampleInputEmail1">E-Posta</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" name="eposta" placeholder="E-Posta">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Şifre</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" name="sifre" placeholder="Şifre">
                    </div>
                    <button type="submit" name="girisyap" class="btn btn-primary btn-block">Giriş Yap</button>
                    </form>
                </div>
            </div>
        </div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
</body>
</html>