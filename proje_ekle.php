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
    $projedengelenid = @$_GET['id'];
    $projedengelenislem = @$_GET['islem'];   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proje İşlem</title>
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
if(isset($_POST["gonder"])){
    if($projedengelenislem == "kaydet"){
        $baslik = $_POST["baslik"];
        $durum = $_POST["durum"];

        if (!file_exists('img')) {
            mkdir('img');
        }

        $yol = "img";

        $yeniad = isimlendir($_FILES["resim"]["name"]);

        $yoladi = __DIR__ . "\\" . $yol . "\\" .$yeniad;

        
       // echo $yoladi; echo "<br>";
      //  echo $yeniad; echo "<br>";
        
        if($durum == ""){ 
            $durum = "pasif";
        }

        if ($_FILES["resim"]["name"]=="") {
             $resim = "";
         } 
        else if (move_uploaded_file($_FILES["resim"]["tmp_name"],$yoladi)) {

            //echo $_FILES["resim"]["tmp_name"];
            
            $resim = isimlendir($_FILES["resim"]["name"]);
         
        } else {
            
        }

        $sorgu = $baglan -> prepare("INSERT INTO projeler SET 
        id = ?, /*? yerine key (anahtar) isimler belirtipte ona göre dizide değer gönderdik*/
        baslik = ?,
        resim = ?,
        durum = ?
        ");
        $ekle = $sorgu -> execute(array(
            NULL, $baslik, $resim, $durum
        ));
        if ( $ekle ){
            $last_id = $baglan->lastInsertId();
            echo "<script>alert('$last_id nolu ekleme işlemi başarılı!')</script>";
            echo "<script>window.location.href='proje_ekle.php'</script>";
        }
    }
}
?>

<p style="text-align:center; margin:10px; display:block;">
    <button class="btn btn-dark" onclick="window.top.location='projeler.php';">Projeler Sayfası</button>
</p>

<form action="proje_ekle.php?islem=kaydet" method="post" enctype="multipart/form-data">

        <p><b>Proje Başlığı:</b></p>
        <input type="text" required name="baslik" value="<?php echo @$satir[baslik]; ?>"><br><br>

        <p><b>Projenin Resmi:</b></p>
        <input class="btn btn-danger" type="file" name="resim"><br><br>

        <p> <b>Projenin Durumu:</b></p>
        <select name="durum">
            <option value="">Seçiniz...</option>
            <option value="aktif" <?php if (@$satir[durum]=="aktif") {echo "selected";} ?>>Aktif</option>
            <option value="pasif"<?php if (@$satir[durum]=="pasif") {echo "selected";} ?>>Pasif</option>
        </select><br><br>

   <!--     <input type="hidden" name="duzenleid" value="<?php// echo @$satir[id]; ?>">-->
  <!--      <input type="hidden" name="eskiresim" value="<?php// echo @$satir[resim]; ?>">-->
        <input class="btn btn-primary" type="submit" value="Kaydet" name="gonder">
    </form>
</body>
</html>
