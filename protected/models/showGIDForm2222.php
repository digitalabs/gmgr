<?php
  class showGIDForm extends CFormModel{
	  
	  public $newGermplasmName;
	  public $germplasmName;
	  
	  public function rules(){
		  return array(
		      	array('newGermplasmName', 'required'),
		  );
	  }
  }
?>
