<?php 
			$username="root";
			$password="GaragePi";
			$database="weatherdata";
 
			$connection = @mysqli_connect('localhost',$username,$password,$database);
			
			if(!$connection)
			{
				echo "Error: " . mysqli_connect_error();
				exit();
			}
 
			$query = "SELECT datetime, temperature, humidity FROM garagesensor";
			$result = mysqli_query($connection, $query);
			$datetime = array();
			$temperature = array();
			$humidity = array();
						
			while ($row = mysqli_fetch_array($result))
				{
				$datetime = $row['datetime'];
				$temperature = $row['temperature'];
				$humidity = $row['humidity'];
				$datatemp[]= "[".$datetime.",".$temperature."]";
				$datahum[]= "[".$datetime.",".$humidity."]";
				}
			
			//echo join($datatemp, ',');
			//echo join($datahum, ',');
			
			mysqli_close();
		?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Home Automation</title>
		<link rel="shortcut icon" href="/data/favicon.ico" />	
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>
		<script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.13/moment-timezone-with-data-2012-2022.min.js"></script>
 
 	<style>

	header, footer {
		font-family: arial, sans-serif;
		padding: 1em;
		width 100%;
		font-size 100%;
		color: white;
		background-color: #22c0e2ff;
		clear: left;
		text-align: center;
	}
	
	div {
    width: 100%;
    }
	
	img {
    max-width: 100%;
    max-height: 100%;
	}


	table {
		font-family: arial, sans-serif;
		border-collapse: collapse;
		width: 100%;
	}

	td, th {
		border: 1px solid #ffffff;
		text-align: center;
		text-color: #1a93aeff;
		padding: 10px;
		width 100%;
	}
	
	input[type=submit] {
		border-radius: 8px;
		border: 0;
		color: white;
		width: 180px;
		height:40px;
		font-family: Tahoma;
		background: #22c0e2ff;
		/* Old browsers */
		background: -moz-linear-gradient(top, #22c0e2ff 100%, #ededed 100%);
		/* FF3.6+ */
		background: -webkit-gradient(linear, left top, left bottom, color-stop(100%, #22c0e2ff), color-stop(100%, #ededed));
		/* Chrome,Safari4+ */
		background: -webkit-linear-gradient(top, #22c0e2ff 100%, #ededed 100%);
		/* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(top, #22c0e2ff 100%, #ededed 100%);
		/* Opera 11.10+ */
		background: -ms-linear-gradient(top, #22c0e2ff 100%, #ededed 100%);
		/* IE10+ */
		background: linear-gradient(to bottom, #22c0e2ff 100%, #ededed 100%);
		/* W3C */
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#22c0e2ff', endColorstr='#ededed', GradientType=0);
		/* IE6-9 */
	}
	</style>
		
	</head>

<body style="background-color: white;">

	<header>
		<h1>Data, Control & Automation System</h1>
	</header>
	<p></p>

	<header>
		<h2>Temperature & Humidity Data</h2>
	</header>
	
	<p></p>

	<script type="text/javascript" width="100%">
		$(function () {
 
		$('#container').highcharts({
		chart: {
			type: 'line',
			renderTo: 'container',
			zoomType: 'x'
			
		},
		title: {
			text: 'Temperature and Humidity Data over Time'
		},
		time: {
			timezone: 'Europe/Dublin'
		},
		xAxis: {
			title: {
				text: 'Time'
			},
			type: 'datetime',
		},
		yAxis: [{ // Primary yAxis
        labels: {
            format: '{value}°C'
            
        },
        title: {
            text: 'Temperature'
           
        },
        
		},
		{ // Secondary yAxis
        labels: {
            format: '{value} %'
           
        },
        title: {
            text: 'Humidity'
           
        },
		opposite: true
		}],
		
		series: [
			{
			name: 'Temperature (°C)',
			data: [<?php echo join($datatemp, ',');?>]
			},
			{
			name: 'Humidity (%)',
			yAxis: 1,
			type: 'spline',
			data: [<?php echo join($datahum, ',');?>]
			}
			]
		});
	});
	</script>

	<script src="charts/js/highcharts.js" style="width:100%"></script>
	<script src="charts/js/modules/exporting.js"  style="width:100%"></script>
 
	<div class="container" style="width:100%">
		<div id="container" style="width:100%"></div>
	</div>
	
	<h3 align="center" >Current Temperature & Humidity</h3>
	
	<table align="center" style="width:100%">
		
		<tr align="center">
			<th></th>
			<th>Temperature = <?php echo file_get_contents("/var/www/html/data/temperature.txt");?>&degC </th>
			<th>Humidity = <?php echo file_get_contents("/var/www/html/data/humidity.txt");?></th>
			<th></th>
		</tr>
	</table>	
	
	<p></p>

	<header>
		<h2>Settings</h2>
	</header>
	
	<p></p>

	<h3 align="center" >Sensor Data Logging</h3>

	<form align="center" method="post">

		<input type="submit" value="Start Data Logging" name="Start">

		<input type="submit" value="Stop Data Logging" name="Stop">

	</form>
	
	<br>

	<h3 align="center" >Power Settings</h3>

	<table align="center" style="width:100%">
		
		<tr align="center">
			<th>Garage Server</th>
			<th>Low Noise Amplifier</th>
			<th>Relay 3</th>
			<th>Relay 4</th>
		</tr>
		
		<tr align="center">
			<td>
				<form align="center" method="post">

					<input type="submit" value="On" name="Relay1On">
					<p></p>
					<input type="submit" value="Off" name="Relay1Off">

				</form>
			</td>

			<td>
				<form align="center" method="post">

					<input type="submit" value="On" name="Relay2On">
					<p></p>
					<input type="submit" value="Off" name="Relay2Off">

				</form>

			</td>

			<td>
				<form align="center" method="post">

					<input type="submit" value="On" name="Relay3On">
					<p></p>
					<input type="submit" value="Off" name="Relay3Off">

				</form>
			</td>
    
			<td>
				<form align="center" method="post">

					<input type="submit" value="On" name="Relay4On">
					<p></p>
					<input type="submit" value="Off" name="Relay4Off">

				</form>
			</td>
		</tr>
	</table>

	<header>
		<h2>Bird Camera Live Stream</h2>
	</header>
	
	<p></p>

	<div align ="center">
		<iframe width="700" height="400" src="https://www.youtube.com/embed/live_stream?channel=UCriCRwOy1V4vkFp2dTTc-4w" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
	</div>
	
	<p></p>
	
	<header>
	<h2>Most Recent Images Captured</h2>
	</header>
	
	<p></p>
	
	<table align="center" width="100%">
		
		<tr align="center" width="100%">
			<?php
			$pictures = glob("data/pi/*.jpg");
			$no_pictures = count($pictures)-1; 
			$limit = $no_pictures-3;            
			for( $i = $no_pictures; $i >= $limit; $i--)
				{
				echo "<th width:"."20%"." >"; 
				if ($i<"0")
					{
					echo "<a href="."data/NoImageFound.jpg"." >"; 
					echo "<img src="."data/NoImageFound.jpg".">"; 
					}
				else
					{
					echo "<a href=".$pictures[$i]." >"; 
					echo "<img src=".$pictures[$i].">"; 
					}	
				echo "</a>";
				echo "<br />";
				if ($i<"0")
					{
					echo "No Further Pictures";
					}
				else
					{
					echo "Picture ".($i+"1");
					}				
				echo "</th>"; 
				}  
			?>
		</tr>
		<tr align="center" width="100%">
			<?php
			$pictures = glob("data/pi/*.jpg");
			$no_pictures = count($pictures)-5; 
			$limit = $no_pictures-3;            
			for( $i = $no_pictures; $i >= $limit; $i--)
				{
				echo "<th width:"."20%"." >"; 
				if ($i<"0")
					{
					echo "<a href="."data/NoImageFound.jpg"." >"; 
					echo "<img src="."data/NoImageFound.jpg".">"; 
					}
				else
					{
					echo "<a href=".$pictures[$i]." >"; 
					echo "<img src=".$pictures[$i].">"; 
					}	
				echo "</a>";
				echo "<br />";
				if ($i<"0")
					{
					echo "No Further Pictures";
					}
				else
					{
					echo "Picture ".($i+"1");
					}				
				echo "</th>"; 
				}  
			?>
		</tr>
		<tr align="center" width="100%">
			<?php
			$pictures = glob("data/pi/*.jpg");
			$no_pictures = count($pictures)-9; 
			$limit = $no_pictures-3;            
			for( $i = $no_pictures; $i >= $limit; $i--)
				{
				echo "<th width:"."20%"." >"; 
				if ($i<"0")
					{
					echo "<a href="."data/NoImageFound.jpg"." >"; 
					echo "<img src="."data/NoImageFound.jpg".">"; 
					}
				else
					{
					echo "<a href=".$pictures[$i]." >"; 
					echo "<img src=".$pictures[$i].">"; 
					}	
				echo "</a>";
				echo "<br />";
				if ($i<"0")
					{
					echo "No Further Pictures";
					}
				else
					{
					echo "Picture ".($i+"1");
					}				
				echo "</th>"; 
				}  
			?>
		</tr>
	</table>
		
	<p></p>
		
	<header>
		<h2>Status</h2>
	</header>

	<p></p>

	<div align="center">

		<?php
			if (isset($_POST['Start']))
			{
				echo"Data logging has started. Temperature and Humidity recorded every 10 minutes";
				system("sudo killall python");
				exec("sudo /usr/bin/python /var/www/html/scripts/TempRead.py");
			}	
			if (isset($_POST['Stop']))
			{
				system("sudo killall python");
				echo"Data logging has now ceased.";
			}
		?>
		
		<?php
			if (isset($_POST['Relay1On']))
			{
				exec("gpio -g mode 17 out");
				exec("gpio -g write 17 1");
				echo"Server power has been activated.";
			}
			if (isset($_POST['Relay1Off']))
			{
				exec("gpio -g mode 17 out");
				exec("gpio -g write 17 0");
				echo"Server power has been isolated.";
			}
			if (isset($_POST['Relay2On']))
			{
				exec("gpio -g mode 18 out");
				exec("gpio -g write 18 1");
				echo"Low noise amplifier has been turned on.";
			}
			if (isset($_POST['Relay2Off']))
			{
				exec("gpio -g mode 18 out");
				exec("gpio -g write 18 0");
				echo"Low Noise Amplifier has been turned off.";
			}
		?>
		
		<?php
			if (isset($_POST['Relay3On']))
			{
				exec("gpio -g mode 24 out");
				exec("gpio -g write 24 1");
				echo"Relay 3 has been turned on.";
			}
			if (isset($_POST['Relay3Off']))
			{
				exec("gpio -g mode 24 out");
				exec("gpio -g write 24 0");
				echo"Relay 3 has been turned off.";
			}
			if (isset($_POST['Relay4On']))
			{
				exec("gpio -g mode 22 out");
				exec("gpio -g write 22 1");
				echo"Relay 4 has been turned on.";
			}
			if (isset($_POST['Relay4Off']))
			{
				exec("gpio -g mode 22 out");
				exec("gpio -g write 22 0");
				echo"Relay 4 has been turned off.";
			}
		?>
	</div>

	<p></p>
	<footer>&copy; Copyright 2018 | Ryan McCartney</footer>

</body>
</html> 

