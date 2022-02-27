<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- iepParentHome.php - IEP Parent Dashboard
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell, Sienna-Rae Johnson
      Date Written: 02/26/2022
      Revised: N/A
    -->
    <title>IEP Portal: Parent Home</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>

  <body>
    <!-- Page is encompassed in grid -->
    <div class="gridContainer">
      <header>
        <!-- Insert logo image here -->
        <h1>IEP Portal</h1>
        <div id="accountHolderInfo">
          <!-- Username, messages button, and account settings button here -->
        </div>
        <div id="horizontalNav">
          <!-- Links are inactive: no further pages have been built -->
          <a class="hNavButton" href=""><h3 class="button">Documents</h3></a>
          <a class="hNavButton" href=""><h3>Goals</h3></a>
          <a class="hNavButton" href=""><h3>Events</h3></a>
          <a class="hNavButton" href=""><h3>Messages</h3></a>
          <a class="hNavButton" href=""><h3>Information</h3></a>
          <a class="hNavButton" href=""><h3>Settings</h3></a>
        </div>
      </header>

      <!-- Vertical navigation bar -->
      <div class="left" id="verticalNav">
        <h3>Navigation</h3>
        <a class="vNavButton" href=""><h3>Child #1</h3></a>
        <a class="vNavButton" href=""><h3>Child #2</h3></a>
        <a class="vNavButton" href=""><h3>Child #3</h3></a>
      </div>

      <!-- Main content of page -->
      <div class="middle" id="mainContent">
        <div class="currentStudentName">
            <h3>Student Name</h3>
        </div>   
        <div class="calendar contentCard">
            <h3>Calendar</h3>

        </div>
        <div class="schedule contentCard">
            <h3>Upcoming</h3>

        </div>
        <h3>Current Goals</h3>
        <div class="contentCard">
          <h4>Goal: Goal Label</h4>
          <p>Goal #1 example text</p>
          <!-- When goal is expanded, Goal Category, Goal Start Date, 
            Goal End Date and Goal Descriptiuon text should be displayed -->

          <div class="contentCard">
              <h5>Objective: Objective Label</h5>

              <!-- When view is expanded, Objective Description text should be displayed -->
              <!-- Progress bar view of data from most recent objective report -->
              <p>Latest Report: (progress) 
                  <progress id="objectiveProgress1" value="5" max="10">5</progress>
              </p>

              <!-- Meter view of data from most recent objective report
                Lisa: I like this one, since it changes color depending on the value 
                min should always be 0 
                max would be the number of observation opportunities
                high is the target number
                low ?
                optimum is probably the same as max
                value is observed in most recent report-->
              <p>Latest Report: (meter) 
                  <meter min="0" max="10" high="7" low="4" optimum="10" value="5">5</meter>
              </p>

              <!-- Whne view is expanded, display graph of report observed values over time -->

              <!-- Button to expand the objective card for more detailed information -->
              <button type="custom" id="objectiveDetails">+</button>
          </div>
          <!-- Button to expand the goal card for mor detailed information -->
          <button type="custom" id="goalDetails">+</button>
        </div>
      
      <footer>
        <!-- Insert footer info here -->
      </footer>

    </div>
  </body>
</html>