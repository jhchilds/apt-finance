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


        $current_data = $finances[count($finances)-1];
        print_r($current_data);



        $current_water = str_replace("$", "",$current_data[2]);

        $current_electric = str_replace("$", "",$current_data[8]);

        $current_rrw = str_replace("$", "",$current_data[7]);

        print "TESTING" . $current_data[0];
