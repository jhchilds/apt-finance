<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Joshua Childs">
    <meta name="description" content="Financial documentation program for broke college kids.">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="author" content="Joshua Childs">
    <link href="css/table.css" type="text/css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="css/form.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>28 South Willard</title>
</head>
<?php
// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
$phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");
$path_parts = pathinfo($phpSelf);

print PHP_EOL . '<!-- include libraries -->' . PHP_EOL;
require_once('lib/security.php');
// Path Parts
if ($path_parts['filename'] == "form") {
    print PHP_EOL . '<!-- include form libraries -->' . PHP_EOL;
    include 'lib/validation-functions.php';
    include 'lib/mail-message.php';
}
print PHP_EOL . '<!-- finished including libraries -->' . PHP_EOL;

if ($path_parts['filename'] != "index") {
    print '<body id="' . $path_parts['filename'] . '">';
}


?>

