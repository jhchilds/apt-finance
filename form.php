<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
     <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
     <meta name="author" content="Joshua Childs">
     <meta name="description" content="Financial documentation program for broke college kids." >
     <meta http-equiv = "X-UA-Compatible" content = "IE=edge">
     <meta charset="utf-8">
     <meta name="author" content="Joshua Childs">
     <link rel="stylesheet" href="css/form.css" >
     <title>28 South Willard</title>
  </head>


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


        $message = '<p>Data Entered:</p>';
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
        $from = 'Burlington Viewshed <jhchilds@uvm.edu>';
        // subject of mail should make sense to your form
        $subject = 'You Registered for Information About Access to the trailheads in the Burlington Viewshed ';

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

        print '<h2>Please Register With Us</h2>';
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
                <legend>Contact Information</legend>




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


            <fieldset class="radio">
                <legend>Age</legend>
                <p>
                  <label class="radio-field">
                    <input type="radio" name="age" value="18 OR Younger" > 18 or Younger
                  </label>
                </p>
                <p>
                  <label class="radio-field">
                    <input type="radio" name="age" value="19" > 19
                  </label>
                </p>
                <p>
                  <label class="radio-field">
                    <input type="radio" name="age" value="20" > 20
                  </label>
                </p>
                <p>
                  <label class="radio-field">
                    <input type="radio" name="age" value="21 OR Older" > 21 or Older
                  </label>
                </p>
            </fieldset>

            <!-- <fieldset class="checkbox">
                <legend>Favorite Bob Quotes</legend>
                <p>
                  <label class="check-field">
                    <input
                        name ="bob"
                        value="Be Cool"
                        type="checkbox"
                        > Be Cool </label>
                </p>

                <p>
                <label class="check-field">
                    <input
                        name ="bob"
                        value="Google It"
                        type="checkbox"
                        > Google It</label>
                </p>
                <p>

               <label class="check-field">
                    <input
                        name ="bob"
                        value="Email Me"
                        type="checkbox"
                        > Email Me</label>

              </p>
              <p>
              <label class="check-field">
                    <input
                        name ="bob"
                        value="Groovy"
                        type="checkbox"
                        > Groovy </label>
                </p>
            </fieldset> -->




            <fieldset class="textarea">
                <legend>Feedback</legend>

                <p>
                    <label for="txtComments" class="required">Comments</label>
                    <textarea id="txtComments" name="txtComments"
                              tabindex="602" onfocus="this.select()"></textarea>
                </p>

            </fieldset>




            <fieldset  class="listbox ">
                <legend>What is your favorite dining hall at UVM?(Optional)</legend>
                <p>

                    <select tabindex="592" size="1">
                        <option value="Harris Millis" >Harris Millis</option>
                        <option value="Simpson" >Simpson</option>
                        <option value="Central"  selected="selected" >Central</option>
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
