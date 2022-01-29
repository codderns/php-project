<?php
    try {
        $baglan = new PDO("mysql:host=localhost;dbname=ismek;charset=utf8", "enter_yourusernameinserver", "enteryourpassword");
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    function isimlendir($metin="") {
        $bul = array("ğ","ı","ü","ş","ö","ç","Ğ","İ","Ü","Ş","Ö","Ç"," ");
        $degistir = array("g","i","u","s","o","c","G","I","U","S","O","C","_");
        $sonuc = str_replace($bul,$degistir,$metin);
        return mb_strtolower($sonuc,"utf8");
    }
?>
