<?php
  class standardTableForm extends CFormModel{
	  
	  public $selectedRows;
	  
	  public function rules(){
		  return array(
		      array('selectedRows','required'),
		  );
	  }
  }
?>
