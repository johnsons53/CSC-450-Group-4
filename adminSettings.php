      <!-- adminSettings.php - IEP Admin Settings
      Spring 100 CSC 450 Capstone, Group 4
      Author: Andy Yang
      Date Written: 03/2/2022 for iepSettings.php
      Revised: 04/18/2022, Intergrated user interface and now displays user information
      Revised: 04/22/2022 Added selectedUserId and selectedUserInfo to access user data sent on Admin accountSelect change
      Revision: 04/29/2022 Changeed iepSettings.php to two views, adminSettings.php and userSettings.php
      Revised: 05/1/2022: Syntax and comment touchup
      
      Resources:
      https://www.webslesson.info/2016/09/php-ajax-display-dynamic-mysql-data-in-bootstrap-modal.html
      https://www.webslesson.info/2016/10/php-ajax-update-mysql-data-through-bootstrap-modal.html
    -->

      <?php

        include_once realpath("initialization.php");
        global $conn;

        // Initialize currentUser as new User of correct type
        // Pass $currentUserId, $currentUserType, $conn into createUser() function
        // See if currentUserId and type exist in Session
        try {
            $currentUserId = $_SESSION["currentUserId"];
        } catch (Exception $e) {
            echo "Message: " . $e->getMessage();
        }

        try {
            $currentUserType = $_SESSION["currentUserType"];
        } catch (Exception $e) {
            echo "Message: " . $e->getMessage();
        }
        try {
            $currentUser = createUser($currentUserId, $currentUserType, $conn);
        } catch (Exception $e) {
            echo "Message: " . $e->getMessage();
        }

        $currentUserName = $currentUser->get_full_name();

        // Selects all because admin view requires table to display all users
        $query = "SELECT * FROM user";
        $result = mysqli_query($conn, $query);

        ?>

      <!DOCTYPE html>
      <html lang="en">

      <head>
          <title>IEP Admin Settings</title>
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
          <link rel="stylesheet" type="text/css" href="style.css">
      </head>

      <body>
          <div class="gridContainer">

              <!-- No header due to admin view being intergrated into admin dashboard -->

              <!-- Table to populate users -->
              <div class="container contentCard">
                  <div class="table-responsive">
                      <br />
                      <div id="usersTable">
                          <table class="table table-bordered">
                              <tr>
                                  <th width="100%">
                                      <h3>Accounts</h3>
                                  </th>
                                  <th width="30%">
                                      <h3>Update </h3>
                                  </th>
                                  <th width="30%">
                                      <h3>View</h3>
                                  </th>
                              </tr>
                              <?php
                                while ($row = mysqli_fetch_array($result)) {
                                ?>
                                  <tr>
                                      <td>
                                          <h4><?php echo $row["user_first_name"] . ' ' . $row["user_last_name"]; ?></h4>
                                      </td>
                                      <td><input type="button" name="update" value="Update" id="<?php echo $row["user_id"]; ?>" class="btn btn-info btn-xs update_data" /></td>
                                      <td><input type="button" name="view" value="View" id="<?php echo $row["user_id"]; ?>" class="btn btn-info btn-xs view_data" /></td>
                                  </tr>
                              <?php
                                }
                                ?>
                          </table>
                      </div>
                  </div>
              </div>
      </body>

      </html>

      <!-- First Modal that displays account information -->
      <div id="showInformationModal" class="modal fade">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title">Account Details</h4>
                  </div>
                  <div class="modal-body" id="userDetail">
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
      </div>

      <!-- Second Modal that updates account information -->
      <div id="updateModal" class="modal fade">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title">Update Information</h4>
                  </div>
                  <div class="modal-body">
                      <form method="post" id="updateForm">
                          <label>Update First Name</label>
                          <input type="text" name="firstname" id="firstname" class="form-control"></input>
                          <label>Update Last Name</label>
                          <input type="text" name="lastname" id="lastname" class="form-control"></input>
                          <label>Update Password</label>
                          <input type="pass" name="password" id="password" class="form-control"></input>
                          <label>Update Email</label>
                          <input type="text" name="email" id="email" class="form-control" />
                          <label>Update Address</label>
                          <input name="address" id="address" class="form-control"></input>
                          <label>Update Phone Number</label>
                          <input type="tel" name="phone" id="phone" maxlength="10" pattern="\d{10}" class="form-control" />
                          <br />
                          <input type="hidden" name="currentAccount" id="currentAccount" />
                          <input type="submit" name="update" id="update" value="Update" class="btn btn-xs btn-success" />
                      </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
      </div>

      <script>
          // Fetches all user information to display in table
          $(document).ready(function() {
              $('#add').click(function() {
                  $('#update').val("Update");
                  $('#updateForm')[0].reset();
              });
              $(document).on('click', '.update_data', function() {
                  var currentAccount = $(this).attr("id");
                  $.ajax({
                      url: "fetch.php",
                      method: "POST",
                      data: {
                        currentAccount: currentAccount
                      },
                      dataType: "json",
                      success: function(data) {
                          $('#firstname').val(data.user_first_name);
                          $('#lastname').val(data.user_last_name);
                          $('#password').val(data.user_password);
                          $('#email').val(data.user_email);
                          $('#address').val(data.user_address);
                          $('#phone').val(data.user_phone);
                          $('#currentAccount').val(data.user_id);
                          $('#update').val("Update");
                          $('#updateModal').modal('show');
                      }
                  });
              });

              // Populates First Modal when account information view button is clicked
              $(document).on('click', '.view_data', function() {
                  var currentAccount = $(this).attr("id");
                  if (currentAccount != '') {
                      $.ajax({
                          url: "select.php",
                          method: "POST",
                          data: {
                            currentAccount: currentAccount
                          },
                          success: function(data) {
                              $('#userDetail').html(data);
                              $('#showInformationModal').modal("show");
                          }
                      });
                  }
              });
          });

          // Sends updated information from Second Modale to database to update, then redisplays table
          $('#update').on("click", function(event) {
              event.preventDefault();
              if ($('#firstname').val() == "") {
                  alert("First name is required");
              } else if ($('#lastname').val() == '') {
                  alert("Last name is required");
              } else if ($('#password').val() == '') {
                  alert("Password is required");
              } else if ($('#email').val() == '') {
                  alert("Email is required");
              } else if ($('#address').val() == '') {
                  alert("Address is required");
              } else if ($('#phone').val() == '') {
                  alert("Phone is required");
              } else {
                  $.ajax({
                      url: "adminInsert.php",
                      method: "POST",
                      data: $('#updateForm').serialize(),
                      beforeSend: function() {
                          $('#update').val("Updating");
                      },
                      success: function(data) {
                          $('#updateForm')[0].reset();
                          $('#updateModal').modal('hide');
                          $('#usersTable').html(data);
                          $('#currentAccount').val();
                          $('html, body').animate({ scrollTop: 0 }, 'fast');
                      }
                  });
              }
          });
      </script>