<?php
    session_start();
    require_once("baglan.php");
?>
<!DOCTYPE html>
<html>
<title>MSB Mimarlık</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">
<body>

<div class="w3-top">
  <div class="w3-bar w3-white w3-wide w3-padding w3-card">
    <a href="#anasayfa" class="w3-bar-item w3-button"><b>MSB</b> Mimarlık</a>
    <div class="w3-right w3-hide-small">
      <a href="#proje" class="w3-bar-item w3-button">Projeler</a>
      <a href="#kurumsal" class="w3-bar-item w3-button">Kurumsal</a>
      <a href="#iletisim" class="w3-bar-item w3-button">İletişim</a>
    </div>
  </div>
</div>

<header class="w3-display-container w3-content w3-wide" style="max-width:1500px;" id="anasayfa">
  <img class="w3-image" src="img/architect.jpg" alt="Mimarlık" width="1500" height="800">
  <div class="w3-display-middle w3-margin-top w3-center">
    <h1 class="w3-xxlarge w3-text-white"><span class="w3-padding w3-black w3-opacity-min"><b>BR</b></span> <span class="w3-hide-small w3-text-light-grey">Mimarlık</span></h1>
  </div>
</header>

<div class="w3-content w3-padding" style="max-width:1564px">
  <div class="w3-container w3-padding-32" id="proje">
    <h3 class="w3-border-bottom w3-border-light-grey w3-padding-16">Projeler</h3>
  </div>

  <div class="w3-row-padding">
      <?php
          $sorgu = $baglan->query("select * from projeler where (durum='aktif') order by baslik asc", PDO::FETCH_ASSOC); 
          if ($sorgu->rowCount()>0) {
              foreach ($sorgu as $satir) {
                  echo "<div class='w3-col l3 m6 w3-margin-bottom'>
                  <div class='w3-display-container'>
                  <div style='text-align:center;' class='w3-display w3-black'>$satir[baslik]</div>
                  <img src='img/$satir[resim]' alt='$satir[baslik]' style='width:100%'>
                  </div>
                  </div>";
              }
          }
      ?>
  </div>

  <div class="w3-container w3-padding-32" id="kurumsal">
    <h3 class="w3-border-bottom w3-border-light-grey w3-padding-16">Hakkımızda</h3>
    <?php
        $sorgu = $baglan->query("select * from kurumsal", PDO::FETCH_ASSOC);
        foreach ($sorgu as $satir) {
            echo "<p>$satir[icerik]</p>";
        }
    ?>
  </div>

  <div class="w3-row-padding w3-grayscale">
    <?php
        $sorgu = $baglan->query("select * from ekip where (durum='aktif') order by adsoyad asc", PDO::FETCH_ASSOC);
        if ($sorgu->rowCount()>0) {
            foreach ($sorgu as $satir) {
                echo "<div class='w3-col l3 m6 w3-margin-bottom'>
                <img src='ekipimg/$satir[resim]' alt='$satir[adsoyad]' style='width:100%;'>
                <h3>$satir[adsoyad]</h3>
                <p class='w3-opacity'>$satir[gorev]</p>
                <p>$satir[aciklama]</p>
                <p><a href='mailto:$satir[iletisim]' class='w3-button w3-light-grey w3-block'>İletişim</a></p>
                </div>";
            }
        }
    ?>
  </div>

  <div class="w3-container w3-padding-32" id="iletisim">
    <h3 class="w3-border-bottom w3-border-light-grey w3-padding-16">İletişim</h3>
    <p>Yeni projeniz üzerinde konuşmak için formu doldurun.</p>
    <form action="#" method="post">
      <input class="w3-input w3-border" type="text" placeholder="Ad Soyad" required name="adsoyad">
      <input class="w3-input w3-section w3-border" type="text" placeholder="E-mail" required name="email">
      <input class="w3-input w3-section w3-border" type="text" placeholder="Konu"  name="konu">
      <textarea class="w3-input w3-section w3-border" type="text" placeholder="Mesaj" required name="mesaj"></textarea>
      <button name="deneme" class="w3-button w3-black w3-section" type="submit">
        <i class="fa fa-paper-plane"></i> GÖNDER
      </button>
    </form>
  </div>
    
  <div class="w3-container">
    <img src="img/map.jpg" class="w3-image" style="width:100%">
  </div>

</div>


<?php
if (isset($_POST["deneme"])){
  $adsoyad = $_POST["adsoyad"];
  $email = $_POST["email"];
  $konu = $_POST["konu"];
  $mesaj = $_POST["mesaj"];
  $ipadres = $_SERVER["REMOTE_ADDR"]; 
  $tarih = date("Y-m-d H:i:s");

  $adsoyad = trim(filter_var($adsoyad,FILTER_SANITIZE_STRING));
  $email = trim(filter_var($email,FILTER_VALIDATE_EMAIL));
  $konu = trim(filter_var($konu,FILTER_SANITIZE_STRING));
  $mesaj = trim(filter_var($mesaj,FILTER_SANITIZE_STRING));

  if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    echo "<script> alert('Hatalı email: $email')</script>";
    echo "<script>window.location.href='index.php'</script>";
  }

  else if(empty($adsoyad) || empty($email) || empty($mesaj) ){
    echo "
    <script> alert('Tüm Alanları Doldurun');
   window.location.href='index.php';
   </script>";
  }
    
  else if($adsoyad=="admin" && $email=="admin@admin.com" && $mesaj=="123456" && $konu ==""){
    echo "<script> alert('Yönetici girişi algılandı.')</script>";
  
    $sorgu = $baglan->query("select * from mimarlik_yonetici",PDO::FETCH_ASSOC);

    //$satir = $sorgu -> execute();
    if ( $sorgu->rowCount() ){

      setcookie("giriscerez","varcerez",time()+60*60);

      foreach( $sorgu as $veri ){
        $_SESSION["kontrol"] = $veri['id'];
        $_SESSION["kullanici"] = $veri['kullanici'];
      }

      

      echo "<script>window.top.location='kontrol.php';</script>";
      die();
 }

  /*
      foreach($satir as $veri){
        $_SESSION["kontrol"] = $veri[0]
        $_SESSION["kullanici"] = $veri[1];

      }
  */
    
  }
  
  else if(!empty($adsoyad) && !empty($email) && !empty($konu)){ 
    $sorgu = $baglan->prepare("insert into iletisim set id=?,adsoyad=?,email=?,konu=?,mesaj=?,ipadres=?,tarih=?");
    $ekle = $sorgu->execute(array(NULL,"$adsoyad","$email","$konu","$mesaj","$ipadres","$tarih"));
    
    $kayitno = $baglan->lastInsertID(); 
    echo "<script>
    alert('$kayitno Sıra Numarası İle Kayıt Altına Alındı.');
    window.location.href='index.php';
    </script>";
  }
  else {
    echo "<script>alert('Tüm Alanları Düzgün Doldurun');
    window.location.href='index.php';
    </script>";

  }
}

?>


<footer class="w3-center w3-black w3-padding-16">
  <p>Powered by <a href="" title="W3.CSS" target="_blank" class="w3-hover-text-green">w3.css</a></p>
</footer>

</body>
</html>
