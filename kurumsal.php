<?php
session_start();
require_once("baglan.php");

if (/*$_COOKIE["giriscerez"]<>"varcerez" || */intval($_SESSION["kontrol"])<=0 || $_SESSION["kullanici"]=="") {
    @header("Location:cikis.php");
    die();
}
$sorgu = $baglan -> query("select * from mimarlik_yonetici where (id='$_SESSION[kontrol]' && kullanici ='$_SESSION[kullanici]')");
if ($sorgu -> rowCount() <= 0) { //eğer herhangi bir bilgi alınmamışsa buradan çıkış
    @header("Location:cikis.php");
    die();
}
?>
<?php
//tabloyu sıralamada asc desc atama değişkene
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
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

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
        table .sira td a{
            background-color: inherit !important;
            color: black !important;
            padding: 5px 10px !important ;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-family: Fantasy !important;
            
        }
        table .sira td a:hover{
            color: green !important;
            background-color: inherit !important;
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
    <hr style="border: solid black 3px; height:0px;">

    <p style="text-align:right; margin:10px; display:block;">

    <button class="btn btn-success" onclick="window.top.location='ekipekle.php?islem=yeni';">Yeni Kişi Ekle</button>
    </p>

    <table width="99%">
        <tr class="sira">
            <!--tıklanınca mesela adsoyad'a üstteki get'in değeri adsoyad olur. 
            ayrıca sirala adlı dizenin ikinci indisinin değeri $yeniyon değişkenine atanır, asc veya desc.-->
            <td width="9%"><b><a href="?ord=id,<?php echo $yeniyon; ?>">ID</a></b></td>
            <td width="10%"><b><a href="?ord=adsoyad,<?php echo $yeniyon; ?>">Ad Soyad</a></b></td>
            <td width="13%"><b>İletişim</b></td>
            <td width="13%"><b><a href="?ord=gorev,<?php echo $yeniyon; ?>">Görev</a></b></td><!--Görev için bir veri tabanı gerekirdi normalde 
        ve oradan bilgiyi çekip buraya aktarılırdı-->
            <td width="20%"><b>Açıklama</b></td>
            <td width="15%"><b>Resim</b></td>
            <td width="8%"><b>Durum</b></td>
            <td width="12%"><b>İşlem</b></td>
        </tr>

    <?php
    
    
    $sorgu = $baglan->query("select * from ekip order by $sirala[0] $sirala[1]",PDO::FETCH_ASSOC); 

    if ($sorgu -> rowCount()){
        foreach ($sorgu as $satir) {
    
            echo "<tr class='sira'>
            <td>$satir[id]</td>
            <td>$satir[adsoyad]</td>
            <td>$satir[iletisim]</td>
            <td>$satir[gorev]</td>
            <td>$satir[aciklama]</td>
            <td><img src='ekipimg/$satir[resim]' height='90' width='135'></td>
            <td>$satir[durum]</td> 
            <td> 
            <a style='color:#2f7dff !important;' href='ekipduzenle.php?islem=duzenle&id=$satir[id]'>Düzenle</a>
            <a style='color:#2f7dff !important;' href='ekipduzenle.php?islem=sil&id=$satir[id]' onclick='if (!confirm(\"Silmek İstediğinize Emin misiniz?\")) {return false;}'>Sil</a>
            </td>
            
            </tr>";
   // ?islem=duzenle demeye gerek kalmadı çünkü düzenle işlemini ayrı sayfada oluşturdum, silme
   //işlemi de duzenle ile aynı sayfada açılmadan olacak, gerek var.
        }
    }
    ?>
    </table>

<p style="margin-top:20px;margin-bottom:40px">Toplam <?php echo $sorgu -> rowCount(); ?> Kişi Listeleniyor...</p>


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
</body>
</html>