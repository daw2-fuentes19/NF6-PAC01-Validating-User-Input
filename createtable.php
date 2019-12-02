<!doctype html>
<html>
<head></head>
<body>
<?php

$link = mysqli_connect("localhost", "root");//identifico el usuario
mysqli_select_db($link,'clima') or die(mysqli_error($link));//selecciono la bd
$ok=false;
print_r(explode('-',$_GET["cCiutat"]));
if(sizeof(explode('-',$_GET["cCiutat"]))==2&&$_GET["poblacio"]>0&&$_GET["tAlta"]>=$_GET["tBaixa"]&&$_GET["tBaixa"]<=$_GET["tAlta"]){
    if(sizeof(explode('-',$_GET["data"]))==3&&strlen($_GET["data"])==10){
            if(intval(explode('-',$_GET["data"])[0])>=2000){

                    $ok=true; 
                    $sql='INSERT INTO ciutat(codi,nom,poblacio) VALUES("'.$_GET["cCiutat"].'","'.$_GET["nom"].'",'.$_GET["poblacio"].')';
                     //$sql='INSERT INTO temps(cfCiutat, tempAlta, tempBaixa, precipitacio, data, color) VALUES( '.$_GET["cCiutat"].','.$_GET["poblacio"].', '.$_GET["tAlta"].','.$_GET["tBaixa"].','.$_GET["precipitacio"].',"'.$_GET["data"].'",1)'; 
                    echo $sql;
                    echo "</br>";
                     if (mysqli_query($link,$sql)) {
                        echo "La query se creó correctamenteEEEEE323232\n";
                    } else {
                        echo 'Error al crear la queryyyyy: ' . mysqli_error($link) . "\n";
                    }
                    
                    $sql='INSERT INTO temps(cfCiutat,tempAlta,tempBaixa,precipitacio,data,color)VALUES('.mysqli_insert_id($link).','.$_GET["tAlta"].','.$_GET["tBaixa"].','.$_GET["precipitacio"].',"'.$_GET["data"].'",1)';
                    echo $sql;
                    echo "</br>";
                    if (mysqli_query($link,$sql)) {
                        echo "La query se creó correctamenteEEEEE323232\n";
                    } else {
                        echo 'Error al crear la queryyyyy: ' . mysqli_error($link) . "\n";
                    }
                }

        }
   

    }
 

 else {
    $ok=false;
 }

/*
$sql = 'INSERT INTO ciutat
        (idCiutat, codi, nom, poblacio)
    VALUES
        (1, "A-1", "Zaragoza", 2000),
        (2, "B-2", "Barcelona", 1000),
        (3, "C-1", "Madrid", 7000),
        (4, "S-1", "Pamplona",3000),
        (5, "D-3", "Sydney", 5500)';

echo "</br>";
if (mysqli_query($link,$sql)) {
    echo "La query se creó correctamente\n";
} else {
    echo 'Error al crear la query: ' . mysqli_error($link) . "\n";
}*/
/*
$sql = 'INSERT INTO temps
        (idTemps, cfCiutat, tempAlta, tempBaixa, precipitacio, data, color)
    VALUES
        (1, 3, 20, 10, 25,"2015-10-11",2),
        (2, 2, 30, 11, 30, "1987-09-12",1),
        (3, 1, 35, 14, 27, "2001-02-02",3),
        (4, 5, 32, 23, 14, "2002-06-12", 4),
        (5, 4, 40, 1, 69, "2009-11-27", 5)';

echo "</br>";
if (mysqli_query($link,$sql)) {
    echo "La query se creó correctamente\n";
} else {
    echo 'Error al crear la query: ' . mysqli_error($link) . "\n";
}*/
$sentido=$_GET['sentido']==null? "ASC" : $_GET['sentido'];

$sql = 'SELECT codi, nom, poblacio from ciutat ' .( $_GET['orden'] ==null? "" : "ORDER BY ".$_GET['orden']." $sentido");

echo $sql;
if (mysqli_query($link,$sql)) {
    echo "La query se creó correctamente\n";
} else {
    echo 'Error al crear la query: ' . mysqli_error($link) . "\n";
}
$result = mysqli_query($link,$sql) or die(mysqli_error($link));

if($sentido == "ASC") {
    $sentido = "DESC";
} else {
    $sentido = "ASC";
}

$table = '
<div style="text-align: center;">
 <h2>Clima Review Database</h2>
 <table border="1" cellpadding="2" cellspacing="2"
  style="width: 70%; margin-left: auto; margin-right: auto;">
  <tr>
   <th>codi</th>
   <th><a href="createtable.php?orden=nom&sentido=' .$sentido.'">nom</a></th>
   <th>poblacio</th>
  </tr>
';

while ($row = mysqli_fetch_assoc($result)) {
    extract($row);
    

    $table .= <<<ENDHTML
    <tr>
    <td>$codi</td>
    <td>$nom</td>
    <td>$poblacio</td>
</tr>
ENDHTML;
}
echo $table;


echo $sql;
$table = '
<div style="text-align: center;">
 <h2>Clima Review Database</h2>
 <table border="1" cellpadding="2" cellspacing="2"
  style="width: 70%; margin-left: auto; margin-right: auto;">
  <tr>
   <th>Nom Ciutat</th>
   <th>tempAlta</th>
   <th>tempBaixa</th>
   <th>Precipitacio</th>
   <th>data</th>
   <th>color</th>


  </tr>
';
$sql='SELECT * FROM temps a, ciutat b where a.cfCiutat=b.idCiutat';
$result = mysqli_query($link,$sql) or die(mysqli_error($link));
while ($row = mysqli_fetch_assoc($result)) {
    extract($row);
    

    $table .= <<<ENDHTML
    <tr>
    <td>$nom</td>
    <td>$tempAlta</td>
    <td>$tempBaixa</td>
    <td>$precipitacio</td>
    <td>$data</td>
    <td>$color</td>
</tr>
ENDHTML;
}
$table .= <<<ENDHTML
    </table>
    </div>
ENDHTML;
echo $table;

 if($ok==true){
echo "Todos los campos estan validados";

 }else{
    echo "Algunos de los campos no estan validados";
 }

?>

<form action="/prova02/createtable.php">
Codi ciutat <input type="text" name="cCiutat" ><br>
Nom<input type="text" name="nom" ><br>
Poblacio <input type="text" name="poblacio"><br>
Temperatura Alta <input type="text" name="tAlta"><br>
Temperatura Baixa <input type="text" name="tBaixa"><br>
Precipitacio<input type="text" name="precipitacio"><br>
data <input type="text" name="data"><br>
<input type="submit" value="Submit"></form>';
</body>
</html>