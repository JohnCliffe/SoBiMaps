<?

$fh = fopen('SOBI_HUBS.csv','r');

$hubsArray = array();

fgets($fh);

while (!feof($fh))
{
  $hubData = fgetcsv($fh);

  $hubsArray[] = 
    array('name' => trim($hubData[2]),
  	      'address' => trim($hubData[3]),
  	      'latitude' => trim($hubData[10]),
  	      'longitude' => trim($hubData[9]),
		  'racks' => trim($hubData[6])
  	     );

  echo "<pre>";
  print_r($hubsArray);  
  echo "</pre>";
}

$myPropertyMapString = 'var hubs = ' . json_encode($hubsArray) . ';';

file_put_contents("hubs.js", $myPropertyMapString);


?>