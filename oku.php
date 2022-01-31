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
    $gelenid = @$_GET['id'];
    $gelenislem = @$_GET['islem'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesaj İçerik</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <style>
        body{
            text-align:center;
        }
        tbody{
            width:90%;
            display: inline-block;
            margin:2em;
        }
        .sag{
            width:80%
        }
        .sol{
            width:20%;
        }
        .sol p{
            font-weight:bold;
        }
        .menu{
            margin:1em;
        }
        .menu a{
            margin-right:10px; border-radius:10%; font-weight:bold;
        }
    </style>
</head>
<body>
    <div class="menu">
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

    <h3>Tıkladığınız Mesajın Tam İçeriği</h4>
    <table class="table">
        <?php
        if($gelenislem == "oku"){
            $sorgu = $baglan->query("select * from iletisim where (id=$gelenid)",PDO::FETCH_ASSOC);
            if($sorgu -> rowCount()>0){
                foreach($sorgu as $satir){
                    echo "
                        <tr>
                        <td class='sol'>
                            <p>ID NO</p>
                        </td>
                        <td class='sag'>
                            <p>$satir[id]</p>
                        </td>
                        </tr>
                        <tr>
                            <td class='sol'>
                                <p>Ad Soyad</p>
                            </td>
                            <td class='sag'>
                                <p>$satir[adsoyad]</p>
                            </td>
                        </tr>
                        <tr>
                            <td class='sol'>
                                <p>Email</p>
                            </td>
                            <td class='sag'>
                                <p>$satir[email]</p>
                            </td>
                        </tr>
                        <tr>
                            <td class='sol'>
                                <p>Konu</p>
                            </td>
                            <td class='sag'>
                                <p>$satir[konu]</p>
                            </td>
                        </tr>
                        <tr>
                            <td class='sol'>
                                <p>Tam Mesaj İçeriği</p>
                            </td>
                            <td class='sag'>
                                <p>$satir[mesaj]</p>
                            </td>
                        </tr>
                        <tr>
                            <td class='sol'>
                                <p>IP Adresi</p>
                            </td>
                            <td class='sag'>
                                <p>$satir[ipadres]</p>
                            </td>
                        </tr>
                        <tr>
                            <td class='sol'>
                                <p>Tarih</p>
                            </td>
                            <td class='sag'>
                                <p>$satir[tarih]</p>
                            </td>
                        </tr>
                        ";
                }
                
            }
        }
        else{
            echo "<script>window.location.href='cikis.php'</script>";
        }
        ?>
          
    </table>
</body>
</html>
