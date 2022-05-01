<?php
/* Admin.php - Admin class definition
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Written: 03/22/2022
      Revised: 
      */

     
require_once realpath('User.php');

class Admin extends User {
    // change these to protected
    protected $admin_id;
    protected $admin_active;


    function __construct($u_id, $name, $password, $first_name, $last_name, $email, $phone, $address, $city, $district, $type, 
            $id, $active) {
        
        parent::__construct($u_id, $name, $password, $first_name, $last_name, $email, $phone, $address, $city, $district, $type);

        // specific to Admin
        $this->admin_id = $id;
        $this->admin_active = $active;

    }

    // Getter methods
    function get_admin_id() {
        return $this->admin_id;
    }

    function get_admin_active() {
        return $this->admin_active;
    }

}

?>
