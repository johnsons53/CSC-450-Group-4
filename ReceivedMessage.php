<?php
/*  ReceivedMessage.php - ReceivedMessage class definition
    Spring 100 CSC 450 Capstone, Group 4
    Author: Lisa Ahnell
    Date Written: 03/26/2022
    Revised: 
*/

ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);      

class ReceivedMessage {
    // change these to public
    public $message_id;
    public $message_read;
    
    function __construct($id, $read) {
        $this->message_id = $id;
        $this->message_read = $read;
    }

    // Getter methods
    function get_message_id() {
        return $this->message_id;
    }
    function get_message_read() {
        return $this->message_read;
    }
    // Setter methods--probably don't actually want to include these.
}

?>
