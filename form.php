<!DOCTYPE html>
<?php
$phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");

// break the url up into an array, then pull out just the filename
$path_parts = pathinfo($phpSelf);
?>
<html lang="en">
    <!-- Head -->
    <head>
        <title>Home Page</title>
        <meta charset="utf-8">
        <meta name="author" content="Joshua Childs">
        <meta name="description" content="cs142 LAB2 editing CSS of a form" >
        <!-- style sheet link -->
        <link href="css/form.css" type="text/css" rel="stylesheet" />


    </head>

<?php
$debug = false;


$domain = '//';

$server = htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, 'UTF-8');

$domain .= $server;
if ($debug) {

    print '<p>php Self: ' . $phpSelf;
    print '<p>Path Parts<pre>';
    print_r($path_parts);
    print '</pre></p>';
}

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%

print PHP_EOL . '<!-- include libraries -->' . PHP_EOL;
require_once('lib/security.php');
// Path Parts
if ($path_parts['filename'] == "form") {
    print PHP_EOL . '<!-- include form libraries -->' . PHP_EOL;
    include 'lib/validation-functions.php';
    include 'lib/mail-message.php';
}
print PHP_EOL . '<!-- finished including libraries -->' . PHP_EOL;
?>



<?php

// print '<body id="' . $path_parts['filename'] . '">';

print '<!-- ######################     Start of Body   ################### -->';

// include ('header.php');
// include ('nav.php');

if ($debug) {
    print '<p>DEBUG MODE IS ON</p>';
}
?>

  <body id="form">





<?php
//%^%^%%^%^%^%^%^%^%^%^%^%^%^%^%^%^%%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^
//
// SECTION: 1 Initialize variables
//
// SECTION: 1a.
// We print out the post array so that we can see our form is working.
// if($debug){ // later you can uncomment the if statement
//      print '<p>Post Array:</p><pre>';
//      print_r($_POST);
//      print '</pre>';
//}
//%^%^%^%^%^%^%^%^%^%^%^%^%^
//
//SECTION: 1b Security
//
// define security variable to be used in section 2a.

$thisURL = $domain . $phpSelf;



//%^%^%^%^%^%^%^%^%^%^%^%^%^
//
//SECTION: 1c form variables
//
// Initialize variables one for each form element
// In order they appear on the form
$firstName = "";

$email = "jhchilds@uvm.edu";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^^%^%^%^
//
// Section 1d form error flags
//
// Initialize error flags one for each form element that we validate
// In order they appear in section 1c.
$firstNameERROR = false;

$emailERROR = false;

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^
//
// SECTION: 1e misc variables
// create array to hold error messages filled (if any) in 2d display in 3c

$errorMsg = array();

//  array used to hold form values that will be wrriten to csv file

$dataRecord = array();

// have we mailed the information to the user?

$mailed = false;

//
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2 process for when the form is submitted
//
if (isset($_POST["btnSubmit"])) {

    //@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2a Security
    //
       if (!securityCheck($thisURL)) {
        $msg = '<p>Sorry you cannot access this page. ';
        $msg .= 'Security breach detected and reported.</p>';
        die($msg);
    }



    //@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2b Sanitize (clean) data
    // Remove any potential javascript or html code from users input n the
    // form. Note it is best to follow the same order as declaration in 1c.
    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $firstName;

    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
    $dataRecord[] = $email;



    //@@@@@@@@@@@@@@@@@@@@
    //
    //SECTION: 2c Validation
    //
    // Validation section/ Check each value for possible errors, empty or
    // not what we expect. You will need an IF block for each element you will
    // check (see 1c and 1d). The IF blocks should also be in the
    // order that the elements appear on your form so that the error messgaes
    // will be in the order they appear. errorMsg will be displayed on the form
    // see section 3b. The error flag ($emailERROR) will be used in 3c.


    if ($firstName == "") {
        $errorMsg[] = "FIRST NAME";
        $firstNameERROR = true;
    } elseif (!verifyAlphaNum($firstName)) {
        $errorMsg[] = "Your first name appears to have incorrect characters.";
        $firstNameERROR = true;
    }



    if ($email == "") {
        $errorMsg[] = 'EMAIL ADDRESS';
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = 'Your email address is not in the correct format.';
        $emailERROR = true;
    }



    //@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2d Process Form - Pass Validation
    //
    // Process for when the form passes validation (error message earary is empty)
    //

    if (!$errorMsg) {
        if ($debug)
            print PHP_EOL . '<p>Form is valid</p>';


        //@@@@@@@@@@@@@@@@@@@@
        //
    // SECTION: 2e Save data
        //
    // This block saves data to a CSV file
        //

    $myFolder = 'data/';

        $myFileName = 'registration';

        $fileExt = '.csv';

        $filename = $myFolder . $myFileName . $fileExt;
        if ($debug)
            print PHP_EOL . '<p>filename is ' . $filename;

        // open the file for appendices
        $file = fopen($filename, 'a');

        // write  forms informations
        fputcsv($file, $dataRecord);

        // close  file
        fclose($file);



        //@@@@@@@@@@@@@@@@@@@@
        //
    // SECTION: 2f Create message
        //
    // Build a message to display on the screen in section 3a to mail to the
        // person filling out the form (section 2g).


        $message = '<h2>Below are the specific amounts for this months payments.</h2>';
        foreach ($_POST as $htmlName => $value) {



            $camelCase = preg_split('/(?=[A-Z])/', substr($htmlName, 1));


            $message .= htmlentities($value, ENT_QUOTES, "UTF-8") . '</p>';
        }

        //@@@@@@@@@@@@@@@@@@@@
        //
    // SECTION: 2g Mail to user
        //
    // Process for mailing a message with form data to the user
        // with message built in section 2f.
        //

    $to = $email; // the person who filled out the form
        $cc = '';
        $bcc = '';
        $from = 'Childs Finance <jhchilds@uvm.edu>';
        // subject of mail should make sense to your form
        $subject = 'Rent/Water/Rubbish/Electric: ' . date("d/m/Y");

        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
    } // end form is valid
}   // ends if form was submitted.
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 3 Display form
//
?>

<article id="main">

    <?php
//########################
//
// SECTION: 3a
//
// If it's the first time coming to this form or there are errors we are
// going to display the form.
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
        print '<h2>Thank you for providing your information.</h2>';
        print '<h2>For your records a copy of this data has ';

        if (!$mailed) {
            print "not ";
        }
        print 'been sent to: ' . $email . '</h2>';

        print $message;
    } else {

        print '<h2>Send Finance Data</h2>';
        print '<p class="heading"> Fill out this form to get put on our email list.</p>';

        //#########################
        // SECTION 3b error messages
        //
        //Disply error messages before we print out the form


        if ($errorMsg) {
            print '<div id="errors">' . PHP_EOL;
            print '<h3>YOU FORGOT THESE THINGS IN YOUR FORM:</h3>' . PHP_EOL;
            print '<ol>' . PHP_EOL;
            foreach ($errorMsg as $err) {
                print '<li>' . $err . '</li>' . PHP_EOL;
            }

            print '</ol>' . PHP_EOL;
            print '</div>' . PHP_EOL;
        }

        //####################################
        //
        // SECTION 3c html Form
        //
        /* Display the HTML form. note that the action is to this same page.$phpSelf
          is defined in top.php NOTE the line:
          value="<?php print $email; ?>
          this makes the form sticky by displaying either the initial default value (line ?)
          or the value they typed in (line ?)
          NOTE this line:
          <?php if($emailERROR) print 'class="mistake"'; ?>
          this prints out a css class so that we can highlight the background etc. to
          make it stand out that a mistake happened here.
         */
        ?>

        <form action="<?php print $phpSelf; ?>"
              id="frmRegister"
              method="post">

            <fieldset class="text">
                <legend>Tenant Email</legend>




                <p>
                    <label class="required text-field" for="txtFirstName">Name</label>
                    <input autofocus
                    <?php if ($firstNameERROR) print 'class="mistake"'; ?>
                           id="txtFirstName"
                           maxlength="45"
                           name="txtFirstName"
                           onfocus="this.select()"
                           placeholder="first name"
                           tabindex="100"
                           type="text"
                           value="<?php print $firstName; ?>"
                           >
                </p>



                <p>
                    <label class="required text-field" for="txtEmail">Email</label>
                    <input
                    <?php if ($emailERROR) print 'class="mistake"'; ?>
                        id="txtEmail"
                        maxlength="45"
                        name="txtEmail"
                        onfocus="this.select()"
                        placeholder="email address"
                        tabindex="120"
                        type="text"
                        value="<?php print $email; ?>"
                        >
                </p>

            </fieldset> <!-- ends radio & buttons -->



            <fieldset class="text">
                <legend>Total Due</legend>
                <p>
                    <label class="required text-field" for="txtRent">Rent</label>
                    <input
                        id="txtRent"
                        maxlength="45"
                        name="txtRent"
                        onfocus="this.select()"
                        placeholder="rent amount"
                        tabindex="120"
                        type="text"
                        value="$2000.00"
                        >
                </p>


                <p>
                    <label class="required text-field" for="txtRubbish">Rubbish</label>
                    <input
                        id="txtRubbish"
                        maxlength="45"
                        name="txtRubbish"
                        onfocus="this.select()"
                        placeholder="rubbish amount"
                        tabindex="120"
                        type="text"
                        value="$24.00"
                        >
                </p>


                <p>
                    <label class="required text-field" for="txtWater">Water</label>
                    <input
                        id="txtWater"
                        maxlength="45"
                        name="txtWater"
                        onfocus="this.select()"
                        placeholder="water amount"
                        tabindex="120"
                        type="text"
                        value="$0.00"
                        >
                </p>


                <p>
                    <label class="required text-field" for="txtElectric">Electric</label>
                    <input
                        id="txtElectric"
                        maxlength="45"
                        name="txtElectric"
                        onfocus="this.select()"
                        placeholder="electric amount"
                        tabindex="120"
                        type="text"
                        value="$0.00"
                        >
                </p>

                <p>
                    <label class="required text-field" for="txtOwed">Amount Owed</label>
                    <input
                        id="txtOwed"
                        maxlength="45"
                        name="txtOwed"
                        onfocus="this.select()"
                        placeholder="owed amount"
                        tabindex="120"
                        type="text"
                        value="$0.00"
                        >
                </p>

            </fieldset> <!-- ends radio & buttons -->





            <fieldset  class="listbox ">
                <legend>Pay Status</legend>
                <p>

                    <select tabindex="592" size="1">
                        <option value="Paid" >Paid</option>
                        <option value="Not Paid" >Not Paid</option>
                    </select>
                </p>

            </fieldset>



            <fieldset class="buttons">
                <legend></legend>
                <input type="submit" id="btnSubmit" name="btnSubmit" value="Register" tabindex="982" class="button">
            </fieldset>

        </form>
        <?php
    } //end body submit
    ?>

</article>
</body>
</html>
