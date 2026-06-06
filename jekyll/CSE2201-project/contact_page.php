<?php
//---
# Zakariya Bacchus Contact Page
# URL: /contact_page.php
//---


// Default values for the form fields so the page does not crash on first load
$submitted = false;
$error = '';
$name = $email = $subject = $message = '';

// Check if the user actually submitted the form before we try to read anything
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Grab each field from the form and trim whitespace so we dont get empty spaces as input
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Basic validation to make sure name email and message are filled in
    if (!$name || !$email || !$message) {
        $error = 'Please fill in your name, email and message.';

    // Check that the email actually looks like a real email address
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'That email address does not look right.';

    } else {
        // If everything checks out we mark the form as submitted
        // In a real deployment mail() would go here to actually send the email
        $submitted = true;
    }
}
?>
<!--Declaring my html document type so the browser can render it properly-->
<!DOCTYPE html>

<!--not necessary but setting the language sometimes helps with SEO(Tarico suggested this so i did it)-->
<html lang="en">

<!--setting up my header for my html-->
<head>

  <!--Explicity setting the encoding as type utf-8-->
  <!--Sometimes text can encode weird and cause random errors-->
  <meta charset="UTF-8">

  <!--Setting up the viewport so that the page can scale good on phones as well as desktop-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!--Adding a meta description for SEO so the page shows up properly in search engines-->
  <meta name="description" content="Contact the UG Campus Events admin team.">

  <!-- Setting a Title for this page, helps make everything look professional and legit-->
  <title>Contact Us - UG Event Portal</title>

  <!--My styles file for this page-->
  <!--Each page was built by one group member-->
  <!--We opted for each page to have its own style file due to unique nature of everyone's coding style
      When we tried one global styles file we ended up in a position where some group members commits
      to git broke the entire file for everyone else.

      This happened multiple times and as such we opted for each page to be self contained and have its own
      styles and javascript in the same webpage file

      While we understand this is not the best for structuring we had to arrive at this compromise
      so that the group could work and build collaboratively without breaking the project for everyone
      after every git commit
      -->

  <style>

    /* Using this root element to store root colours stored across the entire webpage*/
    :root {
      --background: #eaffe9; /* lightish green background for the page*/
      --surface: #ffffff; /* white background that is for some of the panels */
      --surface-soft: #dff7e0; /* adding a softer green for some shadows and highlighting */

      /*UG MAIN COLOURS */
      --primary: #022717; /* UG main dark green */
      --primary-panel: #1a3d2b; /* slightly lighter version of the main dark green */
      --gold: #745b04; /* UG gold accent colour used to add some spice and highlights */
      --border: #c1c8c1; /* White ish gray that is used for highlighting the borders */
      --text: #0d1f11; /* UG main text colour, considered using plain black but the dark green looks a bit more on theme */
      --text-soft: #414843; /* Lighter green and is used as my secondary text colour */
    }

    /* Making the padding and borders easier to control*/
    /* Applying this to all elements in the page, if we don't we get an issue where some elements start
    overflowing and looking bad and ugly*/
    /* Found this trick on stack-overflow after stressing a bit */
    * {
      box-sizing: border-box;
    }

    /* styling for my body part of my page */
    body {
      margin: 0; /* Setting the base margin as 0 */
      min-height: 100vh; /* the body needs to take up the full page */
      background: var(--background); /* making use of the bg colour I defined globally */
      color: var(--text); /* making use of global text colour definition */
      font-family: Arial, sans-serif; /* using arial for font, we decided this on a google meet call */
      display: flex;
      flex-direction: column; /* stack header main footer vertically */
    }

    /*setting up styling for the header bar*/
    header {
      background: var(--primary); /* setting the dark green from global vars */
      border-bottom: 4px solid var(--gold); /* adding a nice subtle gold line so the header looks nice */
      color: white; /* all text in header will be white*/
    }

    nav {
      max-width: 1100px; /* Keeps the nav from getting too wide. */
      margin: 0 auto; /* Centers the nav on the page. */
      padding: 16px; /* Adds space inside the nav. */
      display: flex; /* Puts the brand and links beside each other. */
      gap: 16px; /* Adds space between nav items. */
      flex-wrap: wrap; /* Allows the nav to wrap on smaller screens. */
      align-items: center; /* Vertically centers the nav content. */
    }

    /* global styling for the entire main section*/
    main {
      max-width: 1100px;
      margin: 0 auto;
      padding: 16px;
      flex: 1; /* push footer to the bottom of the page */
    }

    /* making the main name bigger and bold*/
    nav b {
      font-size: 24px;
      font-weight: bold;
      margin-right: auto;
    }

    /* styling each link in the nav bar nothing fancy*/
    nav a {
      color: white;
      text-decoration: none;
      font-weight: bold;
    }

    /*Making sure h1 tags use the UG deep green defined in global*/
    h1 { color: var(--primary); }

    /*setting up the heading area at the top of the page so users know where they are*/
    .page-heading {
      margin-bottom: 24px;
    }

    /*setting up the double column grid*/
    .page-grid {
      display: grid; /* Turns the contact area into a grid. */
      grid-template-columns: 1fr 2fr; /* left info column is smaller than the right form column */
      gap: 20px; /* add a little space between columns*/
    }

    /*small panel behind content so things stand out*/
    .panel {
      background: white;
      border: 1px solid var(--border); /* slight border */
      padding: 18px; /* little padding */
      margin-bottom: 20px; /* space between panels */
    }

    /*setting up each contact info row like email phone etc*/
    .info-row {
      margin-bottom: 16px;
    }

    /*styling labels for contact info like email phone etc*/
    .info-label {
      color: var(--primary);
      font-weight: bold;
      display: block;
    }

    /*secondary text under the label*/
    .info-value {
      color: var(--text-soft);
      font-size: 14px;
    }

    /*styling links inside the info panels*/
    .info-value a {
      color: var(--primary-panel);
    }

    /*form field labels*/
    label {
      display: block;
      font-weight: bold;
      color: var(--primary);
      margin-bottom: 4px;
      font-size: 14px;
    }

    /*styling the actual input boxes and textarea*/
    input, textarea {
      width: 100%;
      padding: 8px 10px;
      border: 1.5px solid var(--border);
      font-family: Arial, sans-serif;
      font-size: 15px;
      background: #f9f9f7;
      color: var(--text);
      margin-bottom: 14px;
    }

    /*highlight the box when the user clicks into it*/
    input:focus, textarea:focus {
      outline: none;
      border-color: var(--primary);
      background: white;
    }

    /*make the message box resizable vertically only*/
    textarea {
      resize: vertical;
      min-height: 110px;
    }

    /*styling the submit button*/
    .button {
      width: 100%;
      padding: 12px;
      background: #1B5E20; /* using my button colour */
      color: white;
      border: none; /* no border on button */
      font-weight: bold; /* make text bold */
      font-size: 15px;
      cursor: pointer;
    }

    /*error message styling so user knows something went wrong*/
    .msg-error {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
      padding: 10px 14px;
      margin-bottom: 14px;
      font-size: 14px;
    }

    /*success message styling so user knows the form was sent*/
    .msg-success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
      padding: 10px 14px;
      margin-bottom: 14px;
      font-size: 14px;
    }

    /*generic footer styling*/
    footer {
      background: #d1e9d2;
      border-top: 1px solid var(--border);
      color: var(--text);
      padding: 16px;
      text-align: center;
      margin-top: auto; /* push it to the bottom */
    }

    /*making the page dynamic for mobile*/
    @media (max-width: 800px) {

      /* put the nav links under the title on phone */
      nav b {
        width: 100%;
        margin-right: 0;
      }

      /* make vertical when using on phone, drop from 2 to 1 column*/
      .page-grid {
        grid-template-columns: 1fr;
      }
    }

  </style>
</head>

<!--actual contact page content is in here-->
<body>

  <!-- Declaring the header for the page -->
  <header>
    <nav>
      <b>UG Event Portal</b>
      <a href="https://taricohenry.github.io/CSE2201-Final-Project/">Home  </a>
      <a href="https://taricohenry.github.io/CSE2201-Final-Project/submit_event.html">Submit An Event  </a>
      <a href="https://cse2201-final-project-contact-page.page.gd/">Contact  </a>
      <a href="https://taricohenry.github.io/CSE2201-Final-Project/admin.html">Admin</a>
    </nav>
  </header>

  <!--the actual contact page content-->
  <main>

    <!--page heading so the user knows they are on the contact page-->
    <div class="page-heading">
      <h1>Contact the Admin Team</h1>
      <p>Questions about events, RSVPs, or the portal? Get in touch.</p>
    </div>

    <!--Start of my double column grid-->
    <div class="page-grid">

      <!--setting up the left side with the contact info details-->
      <section>

        <!--email info panel-->
        <div class="panel">
          <div class="info-row">
            <span class="info-label">Email</span>
            <span class="info-value">
              <a href="mailto:events.admin@uog.edu.gy">events.admin@uog.edu.gy</a>
            </span>
          </div>
        </div>

        <!--location info panel-->
        <div class="panel">
          <div class="info-row">
            <span class="info-label">Location</span>
            <span class="info-value">Computer Science Dept, Faculty of Engineering &amp; Technology</span>
            <span class="info-value">Turkeyen Campus, Georgetown, Guyana</span>
          </div>
        </div>

        <!--phone info panel-->
        <div class="panel">
          <div class="info-row">
            <span class="info-label">Phone</span>
            <span class="info-value">+(592) 222-2740</span>
          </div>
        </div>

        <!--office hours info panel-->
        <div class="panel">
          <div class="info-row">
            <span class="info-label">Office Hours</span>
            <span class="info-value">Monday to Friday</span>
            <span class="info-value">8:00 AM - 4:30 PM (AST)</span>
          </div>
        </div>

      </section>

      <!--setting up the right side with the contact form-->
      <!--linking to the panel class so the form sits in a nice white box like the other panels-->
      <section class="panel">

        <!--using php to check if the form was already submitted so we know which thing to show the user-->
        <!--if submitted show the thank you, if not show the actual form-->
        <?php if ($submitted): ?>

          <!--show a green success message with the users name and email so they know it went through-->
          <div class="msg-success">
            Thanks, <strong><?= htmlspecialchars($name) ?></strong>! Your message was received.
            We will get back to you at <strong><?= htmlspecialchars($email) ?></strong> within 24 hours.
          </div>

          <!--give the user a link to go back and send another message if they want-->
          <p><a href="contact.php">Send another message</a></p>

        <?php else: ?>

          <!--heading and a little note telling the user what this form is for-->
          <h2>Send a Message</h2>
          <p style="color: var(--text-soft); font-size: 14px; margin-bottom: 14px;">
            For general enquiries only. To list an event use the <a href="https://taricohenry.github.io/CSE2201-Final-Project/submit_event.html">Submit Event</a> page.
          </p>

          <!--if php caught a validation error up top we show a red error box here so the user knows what to fix-->
          <?php if ($error): ?>
            <div class="msg-error"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <!--the actual form, method POST means the data goes in the request body not the url-->
          <!--action points back to this same page so php can handle the submission-->
          <form method="POST" action="contact.php">

            <!--name field, required so we know who we are talking to-->
            <!--the red star is just a visual cue that the field is required-->
            <label for="name">Name <span style="color: #b71c1c">*</span></label>
            <!--htmlspecialchars keeps the old value in the box if the form fails validation so the user doesnt have to retype everything-->
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" placeholder="e.g. John Doe" required>

            <!--email field, using type email so the browser does basic format checking before we even hit php-->
            <label for="email">Email <span style="color: #b71c1c">*</span></label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="e.g. john@uog.edu.gy" required>

            <!--subject is optional, not everyone knows exactly what to call their question-->
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($subject) ?>" placeholder="e.g. Question about RSVP">

            <!--the main message box, this is the most important part of the form-->
            <!--using textarea (used for longer responses) here instead of input so the user has room to type a proper message-->
            <label for="message">Message <span style="color: #b71c1c">*</span></label>
            <textarea id="message" name="message" rows="5" placeholder="Write your message here..." required><?= htmlspecialchars($message) ?></textarea>

            <!--submit button, clicking this sends the POST request back to this page-->
            <button class="button" type="submit">Send Message</button>

          </form>

        <?php endif; ?>
      </section>

    </div>
  </main>

  <!--my footer-->
  <footer>
    CSE2201-Final-Project, Lecturer: Lenandlar Singh, University of Guyana
  </footer>

</body>
</html>
