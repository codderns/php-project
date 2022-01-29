<?php
    session_start();
    require_once("baglan.php");

     //BU KISMI AYARLA
    //giriş kontrolü yapılsın: 
    if (/*$_COOKIE["giriscerez"]<>"varcerez" ||*/ intval($_SESSION["kontrol"])<=0 || $_SESSION["kullanici"]=="") {
        @header("Location:cikis.php");
        die();
    }
    $sorgu = $baglan -> query("select * from mimarlik_yonetici where (id='$_SESSION[kontrol]' && kullanici ='$_SESSION[kullanici]')");
    if ($sorgu -> rowCount() <= 0) { //eğer herhangi bir bilgi alınmamışsa buradan çıkış
        @header("Location:cikis.php");
        die();
    }

    //tabloyu sıralamada asc desc atama değişkene
    if (isset($_GET["ord"])) {
        $sirala = explode(",",@$_GET["ord"]);
    } else {
        $sirala = explode(",","id,asc");
    }
    if ($sirala[1]=="asc") {$yeniyon = "desc";} else {$yeniyon = "asc";}
?>

<?php
 //DELETE için:
    $gelenid = @$_GET['id'];
    $gelenislem = @$_GET['islem']; //tıklanınca get olacağı için tıklanmayınca uyarı vermesin

    if($gelenislem == "sil"){
        $sorgu = $baglan->prepare("delete from iletisim where id=:idden");
        $sil = $sorgu->execute(array('idden' => $gelenid));
        
        if ($sil) {
            echo "<script>alert('Silindi!')</script>";
            echo  "<script>window.location.href='iletisim.php'</script>";
        } else {echo "<script>alert('Silinemedi!')</script>";
        echo  "<script>window.location.href='iletisim.php'</script>";}
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <style>
        body{
            text-align:center;
            margin:5px;
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
        table tr td{
            font-family: Fantasy;
            background-color: #FFFF;
            color: black;
            padding-bottom:2em;
        }
        table tr td a:hover{
            color: green;
        }
        table{
            margin-top:30px;
            margin-left: auto;
            margin-right: auto;
        }
        div a{margin-top:20px; margin-right:10px; border-radius:10%; font-weight:bold;}
    </style>
    
</head>
<body>
        <div>
    <a href="kontrol.php">Anasayfa</a> <a href="projeler.php">Projeler</a>  <a href="kurumsal.php">Kurumsal</a>  <a href="iletisim.php">Gelen Mesajlar</a>  <a href="yonetici.php">Yöneticiler</a> <a href="cikis.php" onclick="if (!confirm('Çıkış Yapmak İstediğinize Emin misiniz?')) {return false;}">Çıkış</a>
    </div>
    <script>
    function myfunction(){
        var adet = document.getElementsByTagName('a').length;
        var x = document.getElementsByTagName("a");
                for(let i=0;i<adet;i++){
                    x[i].className += "w3-button w3-black ";
                }
    }
    myfunction();
    </script>
<hr style="border: solid black 3px; height:0px;">

<p style="text-align:right; margin:10px; display:block;">

<table width="99%">
        <tr>
            <!--tıklanınca mesela adsoyad'a üstteki get'in değeri adsoyad olur. 
            ayrıca sirala adlı dizenin ikinci indisinin değeri $yeniyon değişkenine atanır, asc veya desc.-->
            <td width="5%"><b><a href="?ord=id,<?php echo $yeniyon; ?>">ID</a></b></td>
            <td width="12%"><b><a href="?ord=adsoyad,<?php echo $yeniyon; ?>">Ad Soyad</a></b></td>
            <td width="12%"><b><a href="?ord=email,<?php echo $yeniyon; ?>">Email</a></b></td>
            <td width="16%"><b><a href="?ord=konu,<?php echo $yeniyon; ?>">Konu</a></b></td>
            <td width="26%"><b><a href="?ord=mesaj,<?php echo $yeniyon; ?>">Mesaj</a></b></td>
            <td width="7%"><b><a href="?ord=ipadres,<?php echo $yeniyon; ?>">IP Adres</a></b></td>
            <td width="9%"><b><a href="?ord=tarih,<?php echo $yeniyon; ?>">Tarih</a></b></td>
            <td width="13%"><b>İşlem</b></td>
        </tr>

    <?php
    
    
    $sorgu = $baglan->query("select * from iletisim order by $sirala[0] $sirala[1]",PDO::FETCH_ASSOC); //buradaki fetch_assoc aslında dize oluşturur 
    //foreach döngüsü ile getirilir bu oluşturulan dize

    //Ayrıca en üstteki php'de yeniyon değişkenine asc veya desc atandı, sonra sıralasın tıklayınca
//yani normalde tablodaki sıra ile gelir ancak tıklayınca o zaman üstteki 
//ANLAMADIĞIM KISIM Sayfa ilk defa açılınca bir GET yok söz konusu. O zaman $sirala dizesi değer
//almaz ve sıralama order by ile nasıl oluyor de hatasız veriyor??

    if ($sorgu -> rowCount() > 0){
        foreach ($sorgu as $satir) {
    
            echo "<tr>
            <td>$satir[id]</td>
            <td>$satir[adsoyad]</td>
            <td>$satir[email]</td>
            <td>$satir[konu]</td>
            <td>";
            if(strlen($satir['mesaj'])>100){
                echo mb_substr(strip_tags($satir['mesaj']),0,100) . "..." ;
            }
            else if(strlen($satir['mesaj'])<100){
                echo mb_substr(strip_tags($satir['mesaj']),0,100) ;
            }
            echo "</td>";
            echo    "<td>$satir[ipadres]</td>
            <td>$satir[tarih]</td>";

            echo "<td>
            <a style='color:blue;' href='iletisim.php?islem=sil&id=$satir[id]' onclick='if (!confirm(\"Silmek İstediğinize Emin misiniz?\")) {return false;}'>Sil</a>
            <a style= 'color:blue;' href='oku.php?islem=oku&id=$satir[id]'>Oku</a>
            </td>";
            echo "</tr>";
        }
    }
    ?>
</table>

<p style="margin-top:20px;margin-bottom:40px">Toplam <?php echo $sorgu -> rowCount(); ?> Mesaj Listeleniyor...</p>

</body>
</html>