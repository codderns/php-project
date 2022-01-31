<?php
session_start();
require_once("baglan.php");

if (/*$_COOKIE["giriscerez"]<>"varcerez" ||*/ intval($_SESSION["kontrol"])<=0 || $_SESSION["kullanici"]=="") {
    @header("Location:cikis.php");
    die();
}
$sorgu = $baglan -> query("select * from mimarlik_yonetici where (id='$_SESSION[kontrol]' && kullanici ='$_SESSION[kullanici]')");
if ($sorgu -> rowCount() <= 0) {
    @header("Location:cikis.php");
    die();
}
?>

<?php

if (isset($_GET["ord"])) {
    $sirala = explode(",",@$_GET["ord"]);
} else {
    $sirala = explode(",","id,asc");
}
if ($sirala[1]=="asc") {$yeniyon = "desc";} else {$yeniyon = "asc";}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <style>
        body{
            text-align:center;
            margin: 5px;
        }
        div a{
            margin-top:20px; margin-right:10px; border-radius:10%; font-weight:bold;
        }
        .basliklar{
            width: max-content;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        table tr td a{
            background-color: inherit;
            color: black;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-family: Fantasy;
            
        }
        table tr td a:hover{
            color: green;
        }
        table tr td{
            font-family: Fantasy;
            background-color: #FFFF;
            color: black;
            padding-bottom:2em;
        }
        
        table{
            margin-top:30px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
    
</head>
<body>
    <div class="basliklar">
    <a href="kontrol.php">Anasayfa</a> <a href="projeler.php">Projeler</a>  <a href="kurumsal.php">Kurumsal</a>  <a href="iletisim.php">Gelen Mesajlar</a>  <a href="yonetici.php">Yöneticiler</a> <a href="cikis.php" onclick="if (!confirm('Çıkış Yapmak İstediğinize Emin misiniz?')) {return false;}">Çıkış</a>
    </div>
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
    <hr style="border: solid black 3px; height:0px;">

    <p style="text-align:right; margin:10px; display:block;">

    <button class="btn btn-success" onclick="window.top.location='proje_ekle.php?islem=yeni';">Yeni Proje Ekle</button>
    </p>


    <table width="99%">
        <tr>
            <td width="10%"><b><a href="?ord=id,<?php echo $yeniyon; ?>">ID</a></b></td>
            <td width="20%"><b><a href="?ord=baslik,<?php echo $yeniyon; ?>">Başlık</a></b></td>
            <td width="35%"><b>Resim</b></td>
            <td width="20%"><b>Durum</b></td>
            <td width="15%"><b>İşlem</b></td>
        </tr>

    <?php
    
    
    $sorgu = $baglan->query("select * from projeler order by $sirala[0] $sirala[1]",PDO::FETCH_ASSOC); 

    if ($sorgu -> rowCount() > 0){
        foreach ($sorgu as $satir) {
    
            echo "<tr>
            <td>$satir[id]</td>
            <td>$satir[baslik]</td>
            <td><img src='img/$satir[resim]' height='90' width='135'></td>
            <td>$satir[durum]</td> 
            <td> 
            <a style='color:blue;' href='proje_duzenle.php?islem=duzenle&id=$satir[id]'>Düzenle</a>
            <a style='color:blue;' href='proje_duzenle.php?islem=sil&id=$satir[id]' onclick='if (!confirm(\"Silmek İstediğinize Emin misiniz?\")) {return false;}'>Sil</a>
            </td>
            </tr>";

        }
    }
    ?>
    </table>

<p style="margin-top:20px;margin-bottom:40px">Toplam <?php echo $sorgu -> rowCount(); ?> Proje Listeleniyor...</p>
</body>


</html>

