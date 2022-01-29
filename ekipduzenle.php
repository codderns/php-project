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
    $g_idi = @$_GET['id'];
    $g_islem = @$_GET['islem'];   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Düzenle Sil</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <style>
        body{
            text-align:center;
            margin: 5px;
        }
        form{
            margin-top:2em;
            margin-bottom:3em;
        }
    </style>
</head>
<body>
    
<?php

//tıklayınca get formdakini alacaktır ve input edince formdaki get, kaydet adında olduğu için
//aşağıdaki if'de işlemler var. neden formdakine de duzenle yazmadık bilemedim :/

if ($g_islem=="duzenle") {
    $sorgu = $baglan->query("select * from ekip where (id='$g_idi')",PDO::FETCH_ASSOC); 
    foreach($sorgu as $satird){

    }
}

if(isset($_POST["gonder"])){
    if($g_islem == "kaydet"){
        $adsoyad = $_POST["adsoyad"];
        $iletisim = $_POST["iletisim"];
        $aciklama = $_POST["aciklama"];
        $gorev = $_POST["gorev"];
        $durum = $_POST["durum"];
        $duzenleid = $_POST["duzenleid"]; //input edince id kaybolur ve burada hafızada
        //tutması için aşağıdaki hidden inputundan alınan bilgileri getirdik.

        // if($durum == ""){ //seçmemişse kişi pasif olsun
        //     $durum = "pasif";
        // }
        
        $yeniad = isimlendir($_FILES["resim"]["name"]);

        $yol = "ekipimg";

        $yoladi = __DIR__ . "\\" . $yol . "\\" . $yeniad;

        if ($_FILES["resim"]["name"]=="") {
            $resim = $_POST["eskiresim"];

        } 
        else {

            if (move_uploaded_file($_FILES["resim"]["tmp_name"],$yoladi)) {
                
                //echo "eski resim:". $_POST["eskiresim"] . "<br>";

                $resim = $yeniad;
                //echo $resim ."<br>";
          
                //Eğer başka bir resim ise yüklenen silmeden önce bu kurala göre silme yap veya yapma
                if ($resim <> $_POST["eskiresim"]) {

                    
                    $sorgu3 =  $baglan -> query("SELECT * FROM ekip",PDO::FETCH_ASSOC);
                    $deger = 0;

                    foreach($sorgu3 as $satir3){
                        if($satir3['resim'] == $resim){
                            $deger = 1;
                        }
                        //echo $satir3['resim']. " : " . $resim ."<br>";
                    }
                 
                    if($deger == 0){
            
                        $yeniyoladi = __DIR__ . "\\" . $yol .  "\\" . $_POST["eskiresim"];
                       
                       //echo "silinecek yol:". $yeniyoladi . "<br>";
                    
                        unlink($yeniyoladi);
                    }
                    else {
                        
                    }
                    $deger = 0;
                }
            } 
            else {
                $resim = $_POST["eskiresim"];
            
            }   
        }

        //UPDATE İÇİN:
        $sorgumuz=$baglan->prepare("UPDATE ekip SET adsoyad=:adsoyad, iletisim=:iletisim, gorev=:gorev, aciklama=:aciklama, resim=:resim, durum=:durum WHERE id=:id");
        $sonuc=$sorgumuz->execute([
        ":adsoyad" => $adsoyad,
        ":iletisim" => $iletisim,
        ":gorev" => $gorev,
        ":aciklama" => $aciklama,
        ":resim" => $resim,
        ":durum" => $durum,
        ":id" => $duzenleid
        ]);

        if ($sonuc) {
            echo $adsoyad. "<br>";
            echo $iletisim ."<br>";
            echo $durum ."<br>";
            echo  $duzenleid;
            echo "<script>alert('$g_idi nolu düzenleme işlemi başarılı!')</script>";
            
            echo "<script>window.location.href='kurumsal.php'</script>";
        } else {
            echo $adsoyad. "<br>";
            echo $iletisim ."<br>";
            echo $gorev. "<br>";
            echo $aciklama ."<br>";
            echo $durum ."<br>";
            echo $duzenleid;
            echo "<script>alert('$g_idi nolu düzenleme işlemi başarısız!')</script>";
            echo "<script>window.location.href='kurumsal.php'</script>";
        }

    }
}

else if($g_islem == "sil"){

    $sorgu2 = $baglan -> query("SELECT * FROM ekip WHERE id=$g_idi",PDO::FETCH_ASSOC);
    $resimadi = "";
    foreach($sorgu2 as $satir2){
        $resimadi = $satir2['resim'];
    }

    $sorgu = $baglan -> prepare("DELETE FROM ekip WHERE id=$g_idi");
    $sil = $sorgu -> execute(array(
        'id'=>$g_idi
    ));

    $sorgu3 =  $baglan -> query("SELECT * FROM ekip",PDO::FETCH_ASSOC);
    $deger = 0;
    foreach($sorgu3 as $satir3){
        if($satir3['resim'] == $resimadi){
            $deger = 1;
        }
    }

    //bağımsız bir koşul:
    if ($resimadi ==""){
        echo "<script>alert('silme işlemi başarılı!')</script>";
        
        echo "<script>window.location.href='kurumsal.php'</script>";
    }

    else if($deger == 0){
        
        $yol = "ekipimg";
        $yoladi = __DIR__ . "\\" . $yol . "\\" . $resimadi;
       
       // echo "$resimadi";
       
        unlink($yoladi);

        echo "<script>alert('silme işlemi başarılı!')</script>";
        
        echo "<script>window.location.href='kurumsal.php'</script>";
    }
      
    else {
        echo "<script>alert('silme işlemi başarılı!')</script>";
        echo "<script>window.location.href='kurumsal.php'</script>";
    }
    $deger = 0;
}
?>

<p style="text-align:center; margin-top:10px; display:block;">
    <button class="btn btn-dark" onclick="window.top.location='kurumsal.php';">Ekip Bilgisi Sayfası</button>
</p>

<form action="ekipduzenle.php?islem=kaydet&id=$g_idi" method="post" enctype="multipart/form-data">

        <p><b>Ad Soyad:</b></p>
        <!--@satir yazma sebebi öyle bir bilgi alınmıyorsa hata olmasın diyedir.-->
        <input type="text" name="adsoyad" value="<?php echo @$satird['adsoyad']; ?>"><br><br>

        <p><b>İletişim Bilgisi:</b></p>
        <input type="text" name="iletisim" value="<?php echo @$satird['iletisim']; ?>"><br><br>

        <p><b>Görev:</b></p>
        <input type="text" name="gorev" value="<?php echo @$satird['gorev']; ?>"><br><br>

        <p><b>Açıklama:</b></p>
        <textarea rows="4" cols="50" type="text" name="aciklama"><?php echo @$satird['aciklama'];?></textarea>
        <br><br>

        <p><b>Üyenin Resmi:</b></p>
        <span style="display:block;">Not: Değişiklik İstemiyosanız Bu Kısmı Boş Bırakınız</span>
        <input class="btn btn-danger" type="file" name="resim"><br><br>

        <p> <b>Durumu:</b></p>
        <select name="durum">
            <option value="">Seçiniz...</option>
            <option value="aktif" <?php if (@$satird[durum]=="aktif") {echo "selected";} ?>>Aktif</option>
            <option value="pasif"<?php if (@$satird[durum]=="pasif") {echo "selected";} ?>>Pasif</option>
        </select><br><br>

        <!--sayfa yenilenince id kaybolur ve bunun önüne geçmek için burada id ve resim bilgisini
    tuttuk. eğer resim değişikliği olacaksa veritabanındaki resmi burada tuttuk-->
        <input type="hidden" name="duzenleid" value="<?php echo @$satird[id]; ?>">
        <input type="hidden" name="eskiresim" value="<?php echo @$satird[resim]; ?>">

        <input style="font-weight:bold;" class="btn btn-primary" type="submit" value="Düzenle" name="gonder">
        
        <button style="font-weight:bold;" class="btn btn-success" onclick="window.top.location='duzenlesil.php';";>Temizle</button>
    </form>
</body>
</html>