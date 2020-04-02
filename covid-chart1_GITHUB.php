<?php
//Notas: el grafico muestra 35 registros, borrar si hay mas de 35 o 70 registros//1ºleer numero de registros// si num_reg<
//$servername = "localhost";// REPLACE with your Database name
//$dbname = "REPLACE_WITH_YOUR_DATABASE_NAME";// REPLACE with Database user//$username = "REPLACE_WITH_YOUR_USERNAME";// REPLACE with Database user password//$password = "REPLACE_WITH_YOUR_PASSWORD";
require("covid19_graph_passwords.php");
$servername  ="localhost";  // Create connection
$conn = new mysqli($servername, $user, $password, $database);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//echo"<br> borrar php_value display_errors 1   de .htaccess despues de depurar errores";


$sql = "SELECT id, infectados,cont_diarios, fallecidos, recuperados,incremento, mitime FROM covid_19 order by mitime desc limit 40";
$result = $conn->query($sql);

while ($data = $result->fetch_assoc()){
     $sensor_data[] = $data;
}


$readings_time = array_column($sensor_data, 'mitime');



// ******* Uncomment to convert readings time array to your timezone ********
/*$i = 0;
foreach ($readings_time as $reading){
    // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
    $readings_time[$i] = date("Y-m-d H:i:s", strtotime("$reading - 1 hours"));
    // Uncomment to set timezone to + 4 hours (you can change 4 to any number)
    //$readings_time[$i] = date("Y-m-d H:i:s", strtotime("$reading + 4 hours"));
    $i += 1;
}*/
 
$value1 = json_encode(array_reverse(array_column($sensor_data, 'infectados')), JSON_NUMERIC_CHECK);
$value11= json_encode(array_reverse(array_column($sensor_data, 'cont_diarios')), JSON_NUMERIC_CHECK);
$value2 = json_encode(array_reverse(array_column($sensor_data, 'fallecidos')), JSON_NUMERIC_CHECK);
$value3 = json_encode(array_reverse(array_column($sensor_data, 'recuperados')), JSON_NUMERIC_CHECK);
$value4 = json_encode(array_reverse(array_column($sensor_data, 'incremento')), JSON_NUMERIC_CHECK);

$reading_time = json_encode(array_reverse($readings_time), JSON_NUMERIC_CHECK);

/*echo $value1;
echo $value2;
echo $value3;
echo $reading_time;*/
//echo $incremento;
$result->free();


$conn->close();
//echo "F1"; 
?>

<!DOCTYPE html>
<html>
 <br>

<a href="YOUR_WEB/covid_19_country.php"><input type="button" value="Enlace a más información sobre esta página"></a>   
<meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <style>
    body {
      min-width: 310px;
    	max-width: 1280px;
    	height: 500px;
      margin: 0 auto;
    }
    h2 {
      font-family: Arial;
      font-size: 2.5rem;
      text-align: center;
    }
  </style>
  <body>
    <h2>Covid-19: Contagios en España</h2>
    <div id="chart-temperature" class="container"></div>
    <div id="chart-humidity" class="container"></div>
    <div id="chart-pressure" class="container"></div>
<script>

var value1 = <?php echo $value1; ?>;
var value11= <?php echo $value11; ?>;
var value2 = <?php echo $value2; ?>;
var value3 = <?php echo $value3; ?>;
var value4 = <?php echo $value4; ?>;
var reading_time = <?php echo $reading_time; ?>;

var chartT = new Highcharts.Chart({
  chart:{ renderTo : 'chart-temperature' },
  title: { text: 'Contagios totales ' },
  series: [{
    showInLegend: false,
    data: value1
  }],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    },
    series: { color: '#059e8a' }
  },
  xAxis: { 
    type: 'datetime',
    categories: reading_time
  },
  yAxis: {
    title: { text: 'Casos totales' }
    //title: { text: 'Temperature (Fahrenheit)' }
  },
  credits: { enabled: false }
});

//porcentajes..............................

var chartH = new Highcharts.Chart({
  chart:{ type:'spline',inverted: false ,renderTo:'chart-humidity' },
  title: { text: 'Porcentaje de incremento diario' },
  series: [{
    showInLegend: false,
    data: value4
  }],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    }
  },
  xAxis: {
    type: 'datetime',
    //dateTimeLabelFormats: { second: '%H:%M:%S' },
    categories: reading_time
  },
  yAxis: {
    title: { text: 'incremento diario(%)' }
  },
  credits: { enabled: false }
});


var chartP = new Highcharts.Chart({
  chart:{ renderTo:'chart-pressure' },
  title: { text: 'Contagiados diarios' },
  series: [{
    showInLegend: false,
    data: value11
  }],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    },
    series: { color: '#18009c' }
  },
  xAxis: {
    type: 'datetime',
    categories: reading_time
  },
  yAxis: {
    title: { text: 'contagiados diarios' }
  },
  credits: { enabled: false }
});

</script>
</body>
</html>