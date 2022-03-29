<?php
/*  Document.php - Document class definition
    Spring 100 CSC 450 Capstone, Group 4
    Author: Lisa Ahnell
    Date Written: 03/26/2022
    Revised: 
*/

ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);      

class Document {
    // change these to protected
    protected $document_id;
    protected $document_name;
    protected $document_date;
    protected $document_creator;
    protected $document_data;

    function __construct($id, $name, $date, $creator, $data) {
        $this->document_id = $id;
        $this->document_name = $name;
        $this->document_date = $date;
        $this->document_creator = $creator;
        $this->document_data = $data;
    }

    // Getter methods
    function get_document_id() {
        return $this->document_id;
    }
    function get_document_name() {
        return $this->document_name;
    }
    function get_document_date() {
        return $this->document_date;
    }
    function get_document_creator() {
        return $this->document_creator;
    }
    function get_document_data() {
        return $this->document_data;
    }
}

?>
