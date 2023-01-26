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
                <li class="breadcrumb-item active" aria-current="page">Video Arama Sonuçları</li>
            </ol>
        </nav>
        <div class="row">

        </div>
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Video Resim</th>
                                <th scope="col">Video Başlık</th>
                                <th scope="col">Video Url</th>
                                <th scope="col">Video Sahibi</th>
                                <th scope="col">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        if(isset($_GET['view'])){
                            $apikey = $arow->apikey;
                            $q = preg_replace('/ /', '+', $_POST['q']);
                            if(!$q){
                                header('Location:index.php');
                            }else{
                                $searchUrl = "https://www.googleapis.com/youtube/v3/search?part=snippet&q=".$q."&type=video&key=".$apikey."&maxResults=10";
                                $response = file_get_contents($searchUrl);
                                $searchResponse = json_decode($response,true);
                                $say = 1;
                                foreach($searchResponse['items'] as $searchResult){
                                    $a = $searchResult['id']['videoId'];
                                    $title = $searchResult['snippet']['title'];
                                    $img = $searchResult['snippet']['thumbnails']['medium']['url'];
                                    $sahip = $searchResult['snippet']['channelTitle'];
                                    ?>
                                    <tr>
                                        <td><?= $say; ?></td>
                                        <td><img src="<?= $img; ?>" class="img-responsive" width="100" height="70"></td>
                                        <td><?= $title; ?></td>
                                        <td><a href="https://www.youtube.com/watch?v=<?= $a; ?>" target="_blank"><?= $a; ?></a></td>
                                        <td><?= $sahip; ?></td>
                                        <td><a href="videolar.php?info=<?= $a; ?>"><i class="fa fa-plus"></i></a></td>
                                    
                                    </tr>

                                    <?php
                                    $say++;
                                }
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
                
<?php require_once 'alt.php'; ?>
