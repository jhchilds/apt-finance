<?php
// ************** Open finances Data ****************************************
$debug = false;
if(isset($_GET["debug"])){
     $debug = true;
}

$myFolder = 'data/';

$myFileName = 'data';

$fileExt = '.csv';

$filename = $myFolder . $myFileName . $fileExt;

if ($debug) print '<p>filename is ' . $filename;

$file=fopen($filename, "r");

if($debug){
    if($file){
       print '<p>File Opened Succesful.</p>';
    }else{
       print '<p>File Open Failed.</p>';
     }
}
     //************** Read Finance Data ****************************************
    if($file){
        if($debug) print '<p>Begin reading data into an array.</p>';

        // read the header row, copy the line for each header row
        // you have.
        $headers[] = fgetcsv($file);

        if($debug) {
            print '<p>Finished reading headers.</p>';
             print '<p>My header array</p><pre>';
             print_r($headers);
             print '</pre>';
     }

     // read all the data
     while(!feof($file)){
         $finances[] = fgetcsv($file);
     }

     if($debug) {
         print '<p>Finished reading data. File closed.</p>';
         print '<p>My data array<p><pre> ';
         print_r($finances);
         print '</pre></p>';
     }
} // ends if file was opened

 //************** Close Finance Data ****************************************
       fclose($file);
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
      <title>28 South Willard</title>
        <meta charset="utf-8">
        <meta name="description" content="SEO...">
        <meta name="author" content="Joshua Childs">
        <link href="css/table.css" type="text/css" rel="stylesheet" />
    </head>

    <body id="table">
        <header>
            <h1>Apartment Finances</h1>
        </header>
            <!-- <h2>Monthly</h2> -->
            <div style="overflow-x: auto;">

          
            <table>
            <?php
            // cleaner than foreach loops
            for ($i = 0; $i < count($headers); $i++){
              print '<tr>';
              for ($j = 0; $j < count($headers[0]); $j++){
                print '<th>' . $headers[$i][$j] . '</th>';
              }
              print '</tr>' .PHP_EOL;
            }


            for ($i = 0; $i < count($finances); $i++){
              print '<tr>';
              for ($j = 0; $j < count($finances[0]); $j++){
                print '<td>' . $finances[$i][$j] . '</td>';
              }
              print '</tr>' .PHP_EOL;
            }

            print '<tr><td colspan="10">' . count($finances) . ' Months </td></tr>';
            ?>
            </table>
          </div>
    </body>
</html>