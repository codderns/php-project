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
<?php
    $projedengelenid = @$_GET['id'];
    $projedengelenislem = @$_GET['islem'];   
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
            margin-top:5em;
        }
    </style>
</head>
<body>
    
<?php

if ($projedengelenislem=="duzenle") {
    $sorgu = $baglan->query("select * from projeler where (id='$projedengelenid')",PDO::FETCH_ASSOC); 
    foreach($sorgu as $satird){

    }
}

if(isset($_POST["gonder"])){
    if($projedengelenislem == "kaydet"){
        $baslik = $_POST["baslik"];
        $durum = $_POST["durum"];
        $duzenleid = $_POST["duzenleid"];
        /*if (!file_exists('img')) {
            mkdir('img');
        }*/

        $yeniad = isimlendir($_FILES["resim"]["name"]);

        $yol = "img";
        $yoladi = __DIR__ . "\\" . $yol . "\\" . $yeniad;

        
        if ($_FILES["resim"]["name"]=="") {
            $resim = $_POST["eskiresim"];
                //UPDATE İÇİN:

                
            $sorgumuz=$baglan->prepare("UPDATE projeler SET baslik=:baslik, resim=:resim, durum=:durum WHERE id=:id");
            $sonuc=$sorgumuz->execute([
            ":baslik"         => $baslik,
            ":resim"      => $resim,
            ":durum"           => $durum,
            ":id"           => $duzenleid
            ]);

        } 
        else {

            if (move_uploaded_file($_FILES["resim"]["tmp_name"],$yoladi)) {
                
          //      echo "eski resim: ". $_POST["eskiresim"] . "<br>";
          //      echo "yeni resim: ". $yeniad . "<br>";
                $resim = $yeniad;
                //echo $resim ."<br>";
                        
                        //UPDATE
            
                $sorgumuz=$baglan->prepare("UPDATE projeler SET baslik=:baslik, resim=:resim, durum=:durum WHERE id=:id");
                $sonuc=$sorgumuz->execute([
                ":baslik"         => $baslik,
                ":resim"      => $resim,
                ":durum"           => $durum,
                ":id"           => $duzenleid
                ]);

                //Eğer başka bir resim ise yüklenen silmeden önce bu kurala göre silme yap veya yapma
                if ($resim <> $_POST["eskiresim"]) {

                    
                    $sorgu3 =  $baglan -> query("SELECT * FROM projeler",PDO::FETCH_ASSOC);
                    $deger = 0;

                    foreach($sorgu3 as $satir3){
                        if($satir3['resim'] == $_POST["eskiresim"]){
                            $deger = 1;
                        }
                        //echo "vbden". $satir3['resim']. " : " . $resim ."<br>";
                    }
                    //echo $deger;
                    if($deger == 0){
            
                        $yeniyoladi = __DIR__ . "\\" . $yol .  "\\" . $_POST["eskiresim"];
                       
                       //echo "silinecek yol:". $yeniyoladi . "<br>";
                    
                        @unlink($yeniyoladi);
                    }
                    else {
                        
                    }
                    $deger = 0;
                }
            } 
            else {
                $resim = $_POST["eskiresim"];

                        //UPDATE İÇİN:
                $sorgumuz=$baglan->prepare("UPDATE projeler SET baslik=:baslik, resim=:resim, durum=:durum WHERE id=:id");
                $sonuc=$sorgumuz->execute([
                ":baslik"         => $baslik,
                ":resim"      => $resim,
                ":durum"           => $durum,
                ":id"           => $duzenleid
                ]);

            }   
        }

        

    if ($sonuc) {
        // echo $baslik. "<br>";
        // echo $resim ."<br>";
        // echo $durum ."<br>";
        // echo  $duzenleid;
        echo "<script>alert('$duzenleid nolu düzenleme işlemi başarılı!')</script>";
        
        echo "<script>window.location.href='projeler.php'</script>";
    } else {
        // echo $baslik. "<br>";
        // echo $resim ."<br>";
        // echo $durum ."<br>";
        // echo $duzenleid;
        echo "<script>alert('$duzenleid nolu düzenleme işlemi başarısız!')</script>";
        echo "<script>window.location.href='projeler.php'</script>";
    }

    }
}

else if($projedengelenislem == "sil"){

    $sorgu2 = $baglan -> query("SELECT * FROM projeler WHERE id=$projedengelenid",PDO::FETCH_ASSOC);
    $resimadi = "";
    foreach($sorgu2 as $satir2){
        $resimadi = $satir2['resim'];
    }
    
    $sorgu = $baglan -> prepare("DELETE FROM projeler WHERE id=$projedengelenid");
    $sil = $sorgu -> execute(array(
        'id'=>$projedengelenid
    ));

    $sorgu3 =  $baglan -> query("SELECT * FROM projeler",PDO::FETCH_ASSOC);
    $deger = 0;
    foreach($sorgu3 as $satir3){
        if($satir3['resim'] == $resimadi){ 
            $deger = 1;
        }
    }
   
    if ($resimadi==""){
        echo "<script>alert('silme işlemi başarılı!')</script>";
        
        echo "<script>window.location.href='projeler.php'</script>";
        die();
    }

    else if($deger == 0){

        
        $yol = "img";
        $yoladi = __DIR__ . "\\" . $yol ."\\". $resimadi;
        echo $yoladi;
       // echo "$resimadi";
       
        unlink($yoladi);

        echo "<script>alert('silme işlemi başarılı!')</script>";
        
        echo "<script>window.location.href='projeler.php'</script>";
    }
        /*
        $yol = "img";
        $yoladi = __DIR__ . "\\" . $yol . "\\" . $resimadi;
        @unlink($_FILES[$resimadi]);*/
    
    else {
        echo "<script>alert('silme işlemi başarılı!')</script>";
        echo "<script>window.location.href='projeler.php'</script>";
    }
    $deger = 0;
}
?>

<p style="text-align:center; margin:10px; display:block;">
    <button class="btn btn-dark" onclick="window.top.location='projeler.php';">Projeler Sayfası</button>
</p>

<form action="proje_duzenle.php?islem=kaydet&id=$projedengelenid" method="post" enctype="multipart/form-data">

        <p><b>Yeni Proje Başlığı:</b></p>
        <input type="text" required name="baslik" value="<?php echo @$satird['baslik']; ?>"><br><br>

        <p><b>Projenin Resmi:</b></p>
        <span style="display:block;">Not: Değişiklik İstemiyosanız Bu Kısmı Boş Bırakınız</span>
        <input class="btn btn-danger" type="file" name="resim"><br><br>

        <p> <b>Projenin Durumu:</b></p>
        <select name="durum">
            <option value="">Seçiniz...</option>
            <option value="aktif" <?php if (@$satird[durum]=="aktif") {echo "selected";} ?>>Aktif</option>
            <option value="pasif"<?php if (@$satird[durum]=="pasif") {echo "selected";} ?>>Pasif</option>
        </select><br><br>

        <input type="hidden" name="duzenleid" value="<?php echo @$satird[id]; ?>">
        <input type="hidden" name="eskiresim" value="<?php echo @$satird[resim]; ?>">

        <input style="font-weight:bold;" class="btn btn-primary" type="submit" value="Düzenle" name="gonder">
        
        <button style="font-weight:bold;" class="btn btn-success" onclick="window.top.location='proje_duzenle.php';";>Temizle</button>
    </form>
</body>
</html>
