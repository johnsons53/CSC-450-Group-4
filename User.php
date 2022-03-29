<?php
/* User.php - User class definition
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Written: 03/21/2022
      Revised:
      03/29/2022: Removed old code and testing alerts 
      */
ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);      
require_once realpath('SentMessage.php');
require_once realpath('ReceivedMessage.php');

class User {
    protected $user_id;
    protected $user_name;
    protected $user_password;
    protected $user_first_name;
    protected $user_last_name;
    protected $user_email;
    protected $user_phone;
    protected $user_address;
    protected $user_city;
    protected $user_district;
    protected $user_type;

    // Arrays needed for sent messages and received messages
    public $sent_messages = [];
    public $received_messages = [];

    function __construct($id, $name, $password, $first_name, $last_name, $email, $phone, $address, $city, $district, $type) {
        $this->user_id = $id;
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

        // call methods to fill received_messages, received_messages
        $this->store_sent_messages($id);
        $this->store_received_messages($id);
    }

    // Getter methods

    function get_user_id() {
        return $this->user_id;
    }
    function get_user_name() {
        return $this->user_name;
    }
    function get_user_password() {
        return $this->user_password;
    }
    function get_user_first_name() {
        return $this->user_first_name;
    }
    function get_user_last_name() {
        return $this->user_last_name;
    }
    function get_user_email() {
        return $this->user_email;
    }
    function get_user_phone() {
        return $this->user_phone;
    }
    function get_user_address() {
        return $this->user_address;
    }
    function get_user_city() {
        return $this->user_city;
    }
    function get_user_district() {
        return $this->user_district;
    }
    function get_user_type() {
        return $this->user_type;
    }
    function get_full_name() {
        return $this->get_user_first_name() . " " . $this->get_user_last_name();
    }
    function get_sent_messages() {
        return array_values($this->sent_messages);
    }
    function get_received_messages() {
        return array_values($this->received_messages);
    }

    // Method to collect and store sent messages
    function store_sent_messages($id) {
        // connection to database
        $filepath = realpath('login.php');
        $config = require($filepath);
        $db_hostname = $config['DB_HOSTNAME'];
        $db_username = $config['DB_USERNAME'];
        $db_password = $config['DB_PASSWORD'];
        $db_database = $config['DB_DATABASE'];
    
        // Create connection
        $conn = new mysqli($db_hostname, $db_username, $db_password, $db_database);
    
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // run query to select all messages where objective_id matches

        $sql = "SELECT message_id, message_text, message_date
                FROM message
                WHERE user_id=" . $id;
        //run query
        $result = $conn->query($sql);
        // save each result row as a message object in $received_messages
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // new message object from row data
                $sent_message = new SentMessage($row['message_id'], $row['message_text'], $row['message_date']);
                // Add current SentMessage to sent_message array
                $this->sent_messages[] = $sent_message;
                
            }
        } else {
            //echo "0 sent message results <br />";
        }
        // close connection to database
        $conn->close();

        //echo "Connection closed.<br />";
    }

    // Method to collect and store received messages
    function store_received_messages($id) {
        // connection to database
        $filepath = realpath('login.php');
        $config = require($filepath);
        $db_hostname = $config['DB_HOSTNAME'];
        $db_username = $config['DB_USERNAME'];
        $db_password = $config['DB_PASSWORD'];
        $db_database = $config['DB_DATABASE'];
    
        // Create connection
        $conn = new mysqli($db_hostname, $db_username, $db_password, $db_database);
    
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // run query to select all received messages for this user
        $sql = "SELECT message_id, message_read
                FROM message_recipient
                WHERE user_id=" . $id;
        //run query
        $result = $conn->query($sql);   
        // save each result row as a message object in $received_messages
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // new message object from row data
                $received_message = new ReceivedMessage($row['message_id'], $row['message_read']);
                $this->received_messages[] = $received_message;                
            }
        } else {
            //echo "0 received message results <br />";
        }
        // close connection to database
        $conn->close();

        //echo "Connection closed.<br />";
    }

}

?>
