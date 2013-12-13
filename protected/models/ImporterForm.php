<?php
   class ImporterForm extends CFormModel
   {
	   public $file;
	   public $LoadSampleFile;
	
	   
	 /**
	 * Declares the validation rules.
	 */
	  public function rules(){
		  return array(
		     array('file','file','types'=>'csv'),
			 array('LoadSampleFile', 'required'),
		  );  
	  }
	  //Validation next
	  
   }
   
?>
