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
// include 'top.php';
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
$reason = "Class";
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



$subscribeERROR = false;
$wantERROR = false;
$totalChecked = 0;
$reasonERROR = false;
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
        $rent = filter_var($_POST["txtRent"], ENT_QUOTES, "UTF-8");
        $water = filter_var($_POST["txtWater"], ENT_QUOTES, "UTF-8");
        $rubbish = filter_var($_POST["txtRubbish"], ENT_QUOTES, "UTF-8");
        $electric = filter_var($_POST["txtElectric"], ENT_QUOTES, "UTF-8");
        $amount = filter_var($_POST["txtAmount"], ENT_QUOTES, "UTF-8");


        $occupation = htmlentities($_POST["radOccupation"], ENT_QUOTES, "UTF-8");
        if (isset($_POST["chkListen"])) {
            $learn = true;
            $totalChecked++;
        } else {
            $listen = false;
        }
        if (isset($_POST["chkWork"])) {
            $work = true;
            $totalChecked++;
        } else {
            $work = false;
        }
        if (isset($_POST["chkLearn"])) {
            $learn = true;
            $totalChecked++;
        } else {
            $learn = false;
        }
        $reason = htmlentities($_POST["lstReason"], ENT_QUOTES, "UTF-8");
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






    if ($subscribe != "Subscribe" AND $subscribe != "Only Major Events" AND $occupation != "This One Only") {
        $errorMsg[] = "Please choose an option for receiving emails";
        $subscribeERROR = true;
    }
    if ($totalChecked < 1) {
        $errorMsg[] = 'Please choose at least one option for preference';
        $wantERROR = true;
    }

    if ($reason == "") {
        $errorMsg[] = 'Please choose a reason';
        $reasonERROR = true;
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




    $dataRecord[] = $subscribe;
    $dataRecord[] = $listen;
    $dataRecord[] = $work;
    $dataRecord[] = $learn;
    $dataRecord[] = $reason;
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
        $message = '<h2>Your information.</h2>';
        foreach ($_POST as $htmlName => $value) {

            $message .= '<p>';
            // breaks up form
            //
            $camelCase = preg_split('/(?=[A-Z])/', substr($htmlName, 3));

            foreach ($camelCase as $oneWord) {
                $message .= $oneWord . ' ';
            }

            $message .= ' = ' . htmlentities($value, ENT_QUOTES, "UTF-8") . '</p>';
        // mailing information
        }
        $to = $email;
        $cc = '';
        $bcc = '';

        $from = 'Childs Finance <jhchilds@uvm.edu>';

        // subject of mail
        $subject = 'Rent/Electric' . date("m/d/Y");
        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
    } // end if form is valid
} // ends if form submitted
?>
    <article>
        <h2>Get in Contact With Me!</h2>
<?php

    // Display form if first time or errors
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) {
        print '<h2>Thank you for providing your information.</h2>';
        print '<p>For your records a copy of this data has ';
        if (!$mailed) {
            print "not ";
        }
        print 'been sent:</p>';
        print '<p>To: ' . $email . '</p>';
        print $message;
    } else {
    print '<h3>The Best Way to Get in Contact With Me</h3>';
    print '<p class="form-heading">I will not spam you, I promise</p>';

        if ($errorMsg) {
            print '<div id="errors">' . PHP_EOL;
            print '<h2>Your form has the following mistakes that need to be fixed.</h2>' . PHP_EOL;
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
                               placeholder="Enter rent bill"
                               tabindex="100"
                               type="text"
                               value="<?php print $rent; ?>"
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
                               value="<?php print $water; ?>"
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
                               placeholder="Enter rubbish bill"
                               tabindex="100"
                               type="text"
                               value="<?php print $rubbish; ?>"
                        >
                    </p>

                    <p>
                        <label class ="required text-field" for ="txtElectric">Electric</label>
                            <input
                                <?php if ($electricERROR) print 'class="mistake"'; ?>
                                id = "txtElectric"
                                maxlength= "45"
                                name = "txtElectric"
                                onfocus = "this.select()"
                                placeholder= "Enter electric bill"
                                tabindex = "120"
                                type = "text"
                                value = "<?php print $electric; ?>"
                            >
                    </p>


                    <p>
                        <label class ="required text-field" for ="txtAmount">Amount</label>
                            <input
                                <?php if ($amountERROR) print 'class="mistake"'; ?>
                                id = "txtAmount"
                                maxlength= "45"
                                name = "txtAmount"
                                onfocus = "this.select()"
                                placeholder= "Enter total amount"
                                tabindex = "120"
                                type = "text"
                                value = "<?php print $amount; ?>"
                            >
                    </p>



                </fieldset> <!-- ends contact -->










<!--                ================= radio buttons =================-->
                <fieldset class = "radio <?php if ($subscribeERROR) print ' mistake'; ?>">
                    <legend>Do you want to subscribe to my updates?</legend>
                    <p>
                        <label class="radio-field"><input type="radio" id="radOccupationStudent" name="radSubscribe" value="Subscribe" tabindex="572" <?php if ($subscribe == "Subscribe") echo ' checked="checked" '; ?>>
                            Subscribe</label>
                    </p>

                    <p>
                        <label class="radio-field"><input type="radio" id="radOccupationTeacher" name="radSubscribe" value="Teacher" tabindex="574" <?php if ($subscribe == "Only Major Events") echo ' checked="checked" '; ?>>
                            Only Major Events</label>
                    </p>

                    <p>
                        <label class="radio-field"><input type="radio" id="radOccupationOther" name="radSubscribe" value="Other" tabindex="574" <?php if ($subscribe == "This One Only") echo ' checked="checked" '; ?>>
                            This One Only</label>
                    </p>
                </fieldset>
<!--                    ======================== check ======================-->
                <fieldset class = "checkbox <?php if ($wantERROR) print ' mistake'; ?>">
                    <legend>What is your interest in getting in touch with me? (check at least one and all that apply):</legend>
                    <p>
                        <label class="check-field">
                            <input <?php if ($listen) print " checked "; ?>
                                id="chkListen"
                                name="chkListen"
                                tabindex="420"
                                type="checkbox"
                                value="listen"> I Just Want to Listen!</label>
                    </p>

                    <p>
                        <label class="check-field">
                            <input <?php if ($work) print " checked "; ?>
                                id="chkWork"
                                name="chkWork"
                                tabindex="420"
                                type="checkbox"
                                value="work"> I Would Like To Work With You On Music!</label>
                    </p>

                    <p>
                        <label class="check-field">
                            <input <?php if ($learn) print " checked "; ?>
                                id="chkLearn"
                                name="chkLearn"
                                tabindex="420"
                                type="checkbox"
                                value="learn"> I Want to Learn From You! (I Don't Recommend This Option)</label>
                    </p>
                </fieldset>


<!--                    ======================== list options ======================-->
                <fieldset class="listbox <?php if ($reasonERROR) print ' mistake'; ?>">

                    <legend>Why are you here?</legend>
                    <select id="lstReason"
                            name="lstReason"
                            tabindex="520" >
                        <option <?php if ($reason == "Class") print " selected "; ?>
                            value="Class">Class</option>

                        <option <?php if ($reason == "Friend told me") print " selected "; ?>
                            value="Friend told me">Friend told me</option>

                        <option <?php if ($reason == "Soundcloud") print " selected "; ?>
                            value="Soundcloud">Soundcloud</option>

                        <option <?php if ($reason == "Reddit, somehow") print " selected "; ?>
                            value="Reddit, somehow">Reddit, somehow</option>

                    </select>

                </fieldset>

<!--                    ======================== text ======================-->
                <fieldset class="textarea">
                    <p>
                        <label class="required" for="txtComments">Anything else you want to tell me?</label>
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
                    <input class="button" id = "btnSubmit" name = "btnSubmit" tabindex="900" type = "submit" value = "Email Me!" >
                </fieldset>
    </form>
    <?php
    }
    ?>
    </article>

  </body>
  </html>

