<?php
session_start();
require_once("baglan.php");

if (/*$_COOKIE["giriscerez"]<>"varcerez" || */intval($_SESSION["kontrol"])<=0 || $_SESSION["kullanici"]=="") {
    @header("Location:cikis.php");
    die();
}
$sorgu = $baglan -> query("select * from mimarlik_yonetici where (id='$_SESSION[kontrol]' && kullanici ='$_SESSION[kullanici]')");
if ($sorgu -> rowCount() <= 0) {
    @header("Location:cikis.php");
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body{
            text-align:center;
            margin: 10px;
        }
    </style>
    
</head>
<body>

    <a href="kontrol.php">Anasayfa</a> <a href="projeler.php">Projeler</a>  <a href="kurumsal.php">Kurumsal</a>  <a href="iletisim.php">Gelen Mesajlar</a>  <a href="yonetici.php">Yöneticiler</a> <a href="cikis.php" onclick="if (!confirm('Çıkış Yapmak İstediğinize Emin misiniz?')) {return false;}">Çıkış</a>

    <script>
    function myfunction(){
        var adet = document.getElementsByTagName('a').length;
        var x = document.getElementsByTagName("a");
                for(let i=0;i<adet;i++){
                    x[i].className += "w3-button w3-black";
                    
                }
    }
    myfunction();
    </script>

    <?php echo "<h3 >". "Hoşgeldiniz" . " ". ucwords( $_SESSION['kullanici']) . "</h3>"; ?>
</body>
</html>
