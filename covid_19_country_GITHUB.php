<?php

require("covid19_graph_passwords.php");
//covid_19.php
echo '<h3 style="color:blue;"/>Covid-19 Spain:</h3>';

//leemos archivo 
$archivo = "covid_19_last_infected.txt"; 
//$contador = 0; 

$fp = fopen($archivo,"r"); 
$contador = fgets($fp, 26); 
fclose($fp); 

//echo"Archivo:" .$contador;


ini_set("allow_url_fopen", 1);

$respuesta = file_get_contents('https://coronavirus-tracker-api.herokuapp.com/confirmed');

$respuesta_ES=file_get_contents('https://coronavirus-tracker-api.herokuapp.com/v2/locations?country_code=ES');
/*
{"latest":
      {"confirmed":39885,"deaths":2808,"recovered":0},"locations":[{"id":201,"country":"Spain","country_code":"ES","province":"","last_updated":"2020-03-25T09:31:13.901243Z","coordinates":{"latitude":"40","longitude":"-4"},"latest":{"confirmed":39885,"deaths":2808,"recovered":0}}]}
*/
$data = json_decode($respuesta,true); 
$datos = json_decode($respuesta, true);
$datos_ES = json_decode($respuesta_ES,true); 

echo "<br>Total contagios mundiales :<b> ".$datos["latest"]."</b>";
echo "<br>";
//echo "<br>País: ".$datos["locations"][0]["country"];
//echo "<br>total contagiados: ".$datos["locations"][0]["latest"];
//echo "<br>";
//echo "<br>País: ".$datos["locations"][57]["country"];
//echo "<br>Total contagiados:  ".$datos["locations"][57]["latest"];
//echo "<br>";
//$vc=201;//España
//echo "<br>País:<b> ".$datos["locations"][$vc]["country"]."</b>";
//echo "<br>Total contagiados :<b> ".$datos["locations"][$vc]["latest"]."</b>";
//$infect=$datos["locations"][$vc]["latest"];
//echo "<br>";


//............datos_ES
echo "<br>Total contagidos España: ".$datos_ES["latest"]["confirmed"];
echo "<br>Total fallecidos España: ".$datos_ES["latest"]["deaths"];
$infect=$datos_ES["latest"]["confirmed"];
//.............datos_ES
$falle=0;
$recu=0;

if ( (int)$contador!=$infect )
   {   $porcent=((100*$infect)/$contador)-100;
       echo "<br>Porcentaje incremento=".$porcent."%";
       echo "<br>Se actualiza la base y tambien el fichero";
       $cont_diarios=$infect-$contador;
       echo "<br>Contagiados hoy:$cont_diarios";
       //actualizacion del fichero:
       
          $fp = fopen($archivo,"w+"); 
          fwrite($fp, $infect, 26); 
          fclose($fp); 
//...........................insercion en database..........................


// Conectarse a la base de datos
$con=mysqli_connect("localhost",$user,$password,$database);
//@mysql_select_db($database) or die( "Err2or de conexion"); 
 //Comprobar conexion
 if (!$con)
 { die("FAlló la conexión,error: ". mysqli_connect_error());
 }

/*
$sentencia="SELECT id FROM covid_19 ORDER BY id DESC LIMIT 0,1";
$result=mysqli_query($con,$sentencia);
$num=mysqli_num_rows($result);
echo "<br>.....Consultas en la base de datos.....<br/>";
$row = mysqli_fetch_assoc($result);
echo "Número de registros: ".$row["id"]."<br>";
*/
//no es necesario introducir el timestamp, ya lo inserta automáticamente
//echo"<br>contagiados:".$infect;
$sql = "INSERT INTO covid_19( infectados,cont_diarios, fallecidos, recuperados, incremento ) VALUES ('$infect',$cont_diarios,'$falle','$recu','$porcent')";

$result=mysqli_query($con, $sql);// Ejecutamos la instruccion  //añadido &result=

//$result->free();//      ?????

mysqli_close($con);  //cerramos conexion
}
else
  { 
     // echo "<br>No se actualiza la base,porque el fichero y los datos nuevos son iguales.";
      
  }
  
  


//echo "<br>..............................fin";
?>


<br><br>

<a href="YOUR_WEB/covid-chart1.php"><input type="button" value="Enlace al gráfico"></a>

<?php



echo"<br>";
echo"<br>Notas:";
echo"<br>&emsp;Estudio informática de forma autodidacta . Se ha utilizado lenguaje php con una base de datos tipo mysql,la lectura de ficheros tipo json  , y la representación de los datos en un grafico tipo javascript, alojado en un servidor web.";
echo"<br>Fuentes de los datos:";
echo"<br>&emsp;https://coronavirus-tracker-api.herokuapp.com/confirmed  (Archivo tipo Json)";
echo"<br>&emsp;https://coronavirus-tracker-api.herokuapp.com/v2/locations?country_code=ES (Archivo tipo Json)";
echo"<br>Repositorio Github ";
echo"<br><a href='https://github.com/joguit/covid-19-spain-chart'>&emsp;'https://github.com/joguit/covid-19-spain-chart'</a>";
echo"<br>Descargo de responsabilidad:";
echo"<br>&emsp;Sírvase tener presente que el material que aparece en este sitio de Internet es únicamente de carácter informativo. El autor procurará actualizarlo regularmente, pero no puede garantizar su exactitud en todo momento.";
echo"<br>Agradecimientos:";
echo"<br>&emsp;Agradezco a Hostinger Internacional, Ltd. el uso de esta cuenta gratuita para poder desarrollar este sitio web.";
echo"<br>&emsp;Agradezco a Proton Technologies . el uso de esta cuenta de correo gratuita .";
//contador visitas:
echo"<br>Buzón de sugeriencias:";
//echo"<br>&emsp;graficacovid19@protonmail.com";
echo"<br>&emsp;$email";
$archivo2 = "covid_19_cont.txt"; 
$fp = fopen($archivo2,"r"); 
$contador2 = fgets($fp, 26); 
$contador2=$contador2+1;
  
$fp = fopen($archivo2,"w+"); 
fwrite($fp, $contador2, 26); 
fclose($fp); 

echo"<br><br>Visitas totales a esta página:" .$contador2;


?>