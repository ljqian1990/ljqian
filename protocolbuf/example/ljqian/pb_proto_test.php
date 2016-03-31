<?php
class Person extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields["1"] = "PBString";
    $this->values["1"] = "";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
  }
  function name()
  {
    return $this->_get_value("1");
  }
  function set_name($value)
  {
    return $this->_set_value("1", $value);
  }
  function id()
  {
    return $this->_get_value("2");
  }
  function set_id($value)
  {
    return $this->_set_value("2", $value);
  }
}
class Human extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields["1"] = "Person";
    $this->values["1"] = array();
  }
  function person($offset)
  {
    return $this->_get_arr_value("1", $offset);
  }
  function add_person()
  {
    return $this->_add_arr_value("1");
  }
  function set_person($index, $value)
  {
    $this->_set_arr_value("1", $index, $value);
  }
  function remove_last_person()
  {
    $this->_remove_last_arr_value("1");
  }
  function person_size()
  {
    return $this->_get_arr_size("1");
  }
}
?>