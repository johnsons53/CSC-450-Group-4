<?php
/*  Message.php - Message class definition
    Spring 100 CSC 450 Capstone, Group 4
    Author: Lisa Ahnell
    Date Written: 03/26/2022
    Revised: 
*/

ini_set('display_errors', 1);
error_Messageing(E_ALL|E_STRICT);      

class Message {
    // change these to public
    public $message_id;
    public $message_text;
    public $message_date;

    function __construct($id, $text, $date) {
        $this->message_id = $id;
        $this->message_text = $text;
        $this->message_date = $date;
    }

    // Getter methods
    function get_message_id() {
        return $this->message_id;
    }
    function get_message_text() {
        return $this->message_text;
    }
    // Should be able to get rid of this one
    function get_message_date() {
        return $this->message_date;
    }
    // Setter methods--probably don't actually want to include these.
}

?>
