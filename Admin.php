<?php
/* Admin.php - Admin class definition
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Written: 03/22/2022
      Revised: 
      */

ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);      
require_once realpath('User.php');

class Admin extends User {
    // change these to protected
    protected $admin_id;
    protected $admin_active;


    function __construct($u_id, $name, $password, $first_name, $last_name, $email, $phone, $address, $city, $district, $type, 
            $id, $active) {
        
        // carried over from User
        $this->user_id = $u_id;
        $this->user_name = $name;
        $this->user_password = $password;
        $this->user_first_name = $first_name;
        $this->user_last_name = $last_name;
        $this->user_email = $email;
        $this->user_phone = $phone;
        $this->user_address = $address;
        $this->user_city = $city;
        $this->user_district = $district;
        $this->user_type = $type;

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
