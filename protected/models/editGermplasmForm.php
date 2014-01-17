<?php
 Yii::import('application.modules.curl');

  class editGermplasmForm extends CFormModel{
	  
	  public $newGermplasmName;
	  public $germplasmName;
	  
	  private $_new;
	  private $_germplasm;
	  private $list_array;
	  private $output;
	  
	  public function rules(){
		  return array(
		      	array('newGermplasmName, germplasmName', 'required'),
				array('newGermplasmName','authenticate'),
		  );
	  }
	  
      public function attributesLabels(){
          return array('newGermplasmName' => 'Must not be empty');
      }
	  /* Validates the new germplasm supplied by the user
	     This is the 'validate' validator as declared in rules().
	  */
	  public function authenticate($attribute,$params){
	      if(!$this->hasErrors()){
			   if((isset($this->newGermplasmName))){
					   $this->_germplasm = $this->germplasmName;
					   $this->_new = $this->newGermplasmName;
					   $this->list_array = json_decode($_POST['list']);
					   
					   $this->output = $this->callCurl($this->_new, $this->list_array, $this->_germplasm);
					   //var_dump($this->output);
					   $this->list_array = $this->output['list'];
					   if($this->output['updated'] == true){
					       echo "updated joanie";
							   ?>
								<body onload="storeLocal()">
								</body>
								<script type="text/javascript">
									function storeLocal() {
										if ('localStorage' in window && window['localStorage'] != null) {
											try {
												console.log(JSON.stringify(<?php echo json_encode($this->list_array); ?>));
												localStorage.setItem('list', JSON.stringify(<?php echo json_encode($this->list_array); ?>));
											} catch (e) {
												if (e === QUOTA_EXCEEDED_ERR) {
													alert('Quota exceeded!');
												}
											}
										} else {
											alert('Cannot store user preferences as your browser do not support local storage');
										}
									}
									window.addEventListener('storage', storageEventHandler, false);
									function storageEventHandler(event) {
										storeLocal();
									}
								</script>
								<?php
								header("Location: /GMGR/index.php?r=site/output");
								// Yii::app()->createUrl("site/standardTable");
								 die();
				   }else{
					  $this->addError('newGermplasmName','Entered germplasm name is not correct.');
				   }
			   }else{
					  $this->addError('newGermplasmName','Entered germplasm name is not correct.');
				   }
		   }
	  }
	  public function callCurl($new,$list_arr,$old){
	        // echo "new:" . $new;

			$a = array('new' => $new, 'list' => $list_arr, 'old' => $old);
			$data = json_encode($a);
			$curl = new curl();
			$list = $curl->updateGermplasmName($data);

			return $list;
	  }
  }
?>