<?php 

require_once "ayar.php";

function post($parametre,$kosul = false){
    if($kosul == false){
        $sonuc = strip_tags(trim($_POST[$parametre]));
    }elseif($kosul == true){
        $sonuc = strip_tags(trim(addslashes($_POST[$parametre])));
    }
    return $sonuc;
}

function get($parametre,$kosul = false){
    if($kosul == false){
        $sonuc = trim(strip_tags($_GET[$parametre]));
    }else if($kosul == true){
        $sonuc = addslashes(trim(strip_tags($_GET[$parametre])));
    }
    return $sonuc;
}

function sef_link($str){
    $preg = array("Ç","Ş","Ğ","Ü","İ","Ö","ç","ş","ğ","ü","ö","ı","+","#",".");
    $match = array("c","s","g","u","i","o","c","s","g","u","o","i","plus","sharp","");
    $perma = strtolower(str_replace($preg,$match,$str));
    $perma = preg_replace("@[^A-Za-z0-9\-_\.\+]@i"," ",$perma);
    $perma = trim(preg_replace('/\s+/'," ",$perma));   
    $perma = str_replace(' ','-',$perma);
    return $perma;
}

function IP(){
    if(getenv("HTTP_CLIENT_IP")){
        $ip =  getenv("HTTP_CLIENT_IP");
    }elseif(getenv("HTTP_X_FORWARDED_FOR")){
        $ip = getenv("HTTP_X_FORWARDED_FOR");
        if(strstr($ip,',')){
            $tmp = explode(',',$ip);
            $ip = trim($tmp[0]);
        }
    }else{
        $ip = getenv("REMOTE_ADDR");
    }
    return $ip;
}

?>