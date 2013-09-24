<?php
   class ImporterForm extends CFormModel
   {
	   public $file;
	   
	 /**
	 * Declares the validation rules.
	 */
	  public function rules(){
		  return array(
		     array('file','file','types'=>'csv'),
		  );  
	  }
	  //Validation next
	  
   }
   
?>
