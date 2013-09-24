<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name;
$appUser = Yii::app()->user;
$userIsGuest = $appUser->isGuest;
?>
 <div style="margin:20px;"> 
            <?php
                foreach(Yii::app()->user->getFlashes() as $key => $message) {
                    echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
                }
            ?>
    <div style="margin-left:15px">
			<br><br>
            <h4 style="font-family: 'Pontano Sans', sans-serif; margin-top: 10px;">Welcome to <?php echo CHtml::encode(Yii::app()->name); ?></h4>

            <div style="font-family: 'Pontano Sans', sans-serif; font-size: 110%;">

            <p> Project development is in progress. </p>

            </div>
    </div>
</div>
