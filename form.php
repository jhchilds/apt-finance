<!DOCTYPE html>
<?php
$phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");
include('read_data.php');
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

// initialize variables
$firstName = "";
$lastName = "";
$email = "";

$rent = "";
$water = "";
$rubbish = "";
$electric = "";
$amount = "";

$subscribe = "Subscribe";
$listen = true;
$work = false;
$learn = false;
$payStatus = "Not Paid";
$comments = '';

// error variables
$firstNameERROR = false;
$lastNameERROR = false;
$emailERROR = false;

$rentERROR = false;
$waterERROR = false;
$rubbishERROR = false;
$electricERROR = false;
$amountERROR = false;

$payStatusERROR = false;
$commentsERROR = false;

// create array for error messages
$errorMsg = array();

// variable to see if email has been mailed
$mailed = false;

// check if form has been submitted
if (isset($_POST["btnSubmit"])) {
    $thisURL = $domain . $phpSelf;
    if (!securityCheck($thisURL)) {
        $msg = '<p>Sorry you cannot access this page.</p>';
        $msg.= '<p>Security breach detected and reported.</p>';
        die($msg);
    }
// create variables to sanitize data
        $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
        $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
        $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);

        $rent = htmlentities($_POST["txtRent"], ENT_QUOTES, "UTF-8");
        $water = htmlentities($_POST["txtWater"], ENT_QUOTES, "UTF-8");
        $rubbish = htmlentities($_POST["txtRubbish"], ENT_QUOTES, "UTF-8");
        $electric = htmlentities($_POST["txtElectric"], ENT_QUOTES, "UTF-8");
        $amount = htmlentities($_POST["txtAmount"], ENT_QUOTES, "UTF-8");



        $payStatus = htmlentities($_POST["lstStatus"], ENT_QUOTES, "UTF-8");
        $comments = htmlentities($_POST["txtComments"], ENT_QUOTES, "UTF-8");

    if ($firstName == "") {
        $errorMsg[] = 'Please enter your first name';
        $firstNameERROR = true;
    } elseif (!verifyAlphaNum($firstName)) {
        $errorMsg[] = "Your first name appears to have extra characters.";
        $firstNameERROR = true;
    }

    if ($lastName == "") {
        $errorMsg[] = 'Please enter your last name';
        $lastNameERROR = true;
    } elseif (!verifyAlphaNum($lastName)) {
        $errorMsg[] = "Your last name appears to have extra characters.";
        $lastNameERROR = true;
    }

    if ($email == "") {
        $errorMsg[] = 'Please enter your email address';
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = 'Your email address appears to be incorrect.';
        $emailERROR = true;
    }

    if ($rent == "") {
        $errorMsg[] = 'Please enter rent bill';
        $rentERROR = true;
    } elseif (!verifyAlphaNum($rent)) {
        $errorMsg[] = "Rent appears to have extra characters.";
        $rentERROR = true;
    }

    if ($water == "") {
        $errorMsg[] = 'Please enter water bill';
        $waterERROR = true;
    } elseif (!verifyAlphaNum($water)) {
        $errorMsg[] = "Water appears to have extra characters.";
        $waterERROR = true;
    }

    if ($rubbish == "") {
        $errorMsg[] = 'Please enter rubbish bill';
        $rubbishERROR = true;
    } elseif (!verifyAlphaNum($rubbish)) {
        $errorMsg[] = "Rubbish appears to have extra characters.";
        $rubbishERROR = true;
    }

    if ($electric == "") {
        $errorMsg[] = 'Please enter electric bill';
        $electricERROR = true;
    } elseif (!verifyAlphaNum($rent)) {
        $errorMsg[] = "Electric appears to have extra characters.";
        $electricERROR = true;
    }

    if ($amount == "") {
        $errorMsg[] = 'Please enter amount';
        $amountERROR = true;
    } elseif (!verifyAlphaNum($amount)) {
        $errorMsg[] = "Amount appears to have extra characters.";
        $amountERROR = true;
    }


    if ($payStatus == "") {
        $errorMsg[] = 'Please choose a staus';
        $payStatusERROR = true;
    }

    if ($comments != "") {
     if (!verifyAlphaNum($comments)) {
        $errorMsg[] = 'Your comment appears to have extra characters that are not allowed.';
        $commentsERROR = true;
        }
    }


    //
    print PHP_EOL . '<!-- SECTION: 2d Process Form - Passed Validation -->' . PHP_EOL;
    //
    // process form when validation passes
    //
    if (!$errorMsg) {
    $dataRecord = array();
    // assign values to array
    $dataRecord[] = $firstName;
    $dataRecord[] = $lastName;
    $dataRecord[] = $email;

    $dataRecord[] = $rent;
    $dataRecord[] = $water;
    $dataRecord[] = $rubbish;
    $dataRecord[] = $electric;
    $dataRecord[] = $amount;



    $dataRecord[] = $payStatus;
    $dataRecord[] = $comments;
    // setup csv file
    $filename = 'data/registration.csv';
    // open file to add date
    $file = fopen($filename, 'a');
    // write info
    fputcsv($file, $dataRecord);
    // close file
    fclose($file);
        // create the email message
        $message = '<header style="background-color: #222;"> 28 South Willard Finances</header><body style = "background-color: #f3f3f3; width: 800px; margin: auto;" >
                    <h1 style="background-color: deeppink;text-align: center; padding: .5em; border-radius: 2em;">'
                    . date("m/d/Y") .' FINANCES:</h1>';

        foreach ($_POST as $htmlName => $value) {

            $message .= '<h2 style="text-align: center">';
            // breaks up form
            //
            $camelCase = preg_split('/(?=[A-Z])/', substr($htmlName, 3));

            foreach ($camelCase as $oneWord) {
                $message .= $oneWord . ' ';
            }

            if (is_numeric($value)){
              $message .= ' = $' . htmlentities(number_format($value,2), ENT_QUOTES, "UTF-8") . '</h2> ';
            }

            else{
              $message .= ' = ' . htmlentities($value, ENT_QUOTES, "UTF-8") . '</h2>';
            }

        // mailing information
        }

        $message .= '</body>' . '</html>';


        $to = $email;
        $cc = '';
        $bcc = '';

        $from = 'Childs Finance <jhchilds@uvm.edu>';

        // subject of mail
        $subject = 'Rent and Utilities: ' . date("m/d/Y");
        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
    } // end if form is valid
} // ends if form submitted
?>
    <article>
        <h2>Send Financial Data</h2>
<?php

    // Display form if first time or errors
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) {
        print '<h2>Success</h2>';
        print '<p>Data sent to ';
        if (!$mailed) {
            print "not ";
        }
        print 'been sent:</p>';
        print '<p>To: ' . $email . '</p>';
        print $message;
    } else {

        if ($errorMsg) {
            print '<div id="errors">' . PHP_EOL;
            print '<h2>MISTAKES</h2>' . PHP_EOL;
            print '<ol>' . PHP_EOL;
            foreach ($errorMsg as $err) {
                print '<li>' . $err . '</li>' . PHP_EOL;
            }
            print '</ol>' . PHP_EOL;
            print '</div>' . PHP_EOL;
        }

    ?>
      <form action = "<?php print $phpSelf; ?>"
            id = "frmRegister"
            method = "post">
                <fieldset class = "text">
                    <legend>Tenant Email</legend>
                    <p>
                        <label class="required text-field" for="txtFirstName">First Name</label>
                        <input autofocus
                               <?php if ($firstNameERROR) print 'class="mistake"'; ?>
                               id="txtFirstName"
                               maxlength="45"
                               name="txtFirstName"
                               onfocus="this.select()"
                               placeholder="Enter your first name"
                               tabindex="100"
                               type="text"
                               value="<?php print $firstName; ?>"
                        >
                    </p>

                    <p>
                        <label class="required text-field" for="txtLastName">Last Name</label>
                        <input
                               <?php if ($lastNameERROR) print 'class="mistake"'; ?>
                               id="txtLastName"
                               maxlength="45"
                               name="txtLastName"
                               onfocus="this.select()"
                               placeholder="Enter your Last name"
                               tabindex="100"
                               type="text"
                               value="<?php print $lastName; ?>"
                        >
                    </p>

                    <p>
                        <label class ="required text-field" for ="txtEmail">Email</label>
                            <input
                                <?php if ($emailERROR) print 'class="mistake"'; ?>
                                id = "txtEmail"
                                maxlength= "45"
                                name = "txtEmail"
                                onfocus = "this.select()"
                                placeholder= "Enter your email"
                                tabindex = "120"
                                type = "text"
                                value = "<?php print $email; ?>"
                            >
                    </p>
                </fieldset> <!-- ends contact -->




                <fieldset class = "text">
                    <legend>Bills Due</legend>
                    <p>
                        <label class="required text-field" for="txtRent">Rent</label>
                        <input autofocus
                               <?php if ($rentERROR) print 'class="mistake"'; ?>
                               id="txtRent"
                               maxlength="45"
                               name="txtRent"
                               onfocus="this.select()"
                               placeholder="2000"
                               tabindex="100"
                               type="text"
                               value="2000"
                               readonly
                        >
                    </p>


                    <p>
                        <label class="required text-field" for="txtWater">Water</label>
                        <input
                               <?php if ($waterERROR) print 'class="mistake"'; ?>
                               id="txtWater"
                               maxlength="45"
                               name="txtWater"
                               onfocus="this.select()"
                               placeholder="Enter water bill"
                               tabindex="100"
                               type="text"
                               value="<?php print $current_water; ?>"
                               readonly
                        >
                    </p>

                    <p>
                        <label class="required text-field" for="txtLastName">Rubbish</label>
                        <input
                               <?php if ($rubbishERROR) print 'class="mistake"'; ?>
                               id="txtRubbish"
                               maxlength="45"
                               name="txtRubbish"
                               onfocus="this.select()"
                               placeholder="24"
                               tabindex="100"
                               type="text"
                               value="24"
                               readonly
                        >
                    </p>

                    <p>
                        <label class ="required text-field" for ="txtElectric">Electric Each</label>
                            <input
                                <?php if ($electricERROR) print 'class="mistake"'; ?>
                                id = "txtElectric"
                                maxlength= "45"
                                name = "txtElectric"
                                onfocus = "this.select()"
                                placeholder= "Enter electric bill"
                                tabindex = "120"
                                type = "text"
                                value = "<?php print $current_electric; ?>"
                                readonly
                            >
                    </p>


                    <p>
                        <label class ="required text-field" for ="txtAmount">Each to Trono</label>
                            <input
                                <?php if ($amountERROR) print 'class="mistake"'; ?>
                                id = "txtAmount"
                                maxlength= "45"
                                name = "txtAmount"
                                onfocus = "this.select()"
                                placeholder= "Enter total amount"
                                tabindex = "120"
                                type = "text"
                                value = "<?php print $current_rrw; ?>"
                                readonly
                            >
                    </p>



                </fieldset> <!-- ends bills -->



<!--                    ======================== list options ======================-->
                <fieldset class="listbox <?php if ($payStatusERROR) print ' mistake'; ?>">

                    <legend>Paid Status</legend>
                    <select id="lstStatus"
                            name="lstStatus"
                            tabindex="520" >
                        <option <?php if ($payStatus == "Not Paid") print " selected "; ?>
                            value="Not Paid">Not Paid</option>

                        <option <?php if ($payStatus == "Paid") print " selected "; ?>
                            value="Paid">Paid</option>

                    </select>

                </fieldset>

<!--                    ======================== text ======================-->
                <fieldset class="textarea">
                    <p>
                        <label class="required" for="txtComments">Comments</label>
                        <textarea <?php if ($commentsERROR) print 'class="mistake"'; ?>
                            id="txtComments"
                            name="txtComments"
                            onfocus="this.select()"
                            tabindex="200"><?php print $comments; ?></textarea>
                    </p>
                </fieldset>

<!--                    ======================== submit ======================-->
                <fieldset class="buttons">
                    <legend></legend>
                    <input class="button" id = "btnSubmit" name = "btnSubmit" tabindex="900" type = "submit" value = "Submit" >
                </fieldset>
    </form>



    <?php
    }
    ?>
    </article>

  </body>
  </html>

