<?php
  class editGermplasmForm extends CFormModel{
	  
	  public $newGermplasmName;
	  public $germplasmName;
	  
	  public function rules(){
		  return array(
		      	array('newGermplasmName, germplasmName', 'required'),
		  );
	  }
  }
?>
