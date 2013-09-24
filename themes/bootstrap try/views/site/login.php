<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
?>



<div id="main-login"> 
    <div style="display: table; margin: 0 auto;">
            <?php
                foreach(Yii::app()->user->getFlashes() as $key => $message) {
                    echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
                }
            ?>
        <!--<div id="logo-carousel">-->
            <?php if(Yii::app()->user->isGuest){ ?>
           <!-- <div id="logo2">
                <a href="">
                    <?php
                    // $image = CHtml::image(Yii::app()->baseUrl.'/themes/irri/img/bims-logo2.png', "International Rice Research Institute");
                    // echo CHtml::link($image, array('/site/index'));
                    ?>
                </a>
            </div>-->
            <?php } ?>
            <!--<p style="font-weight: bold;">Welcome to IRRI Breeding Information Management Systems (BIMS)</p>-->
            <!--<div id="carousel">-->
            <?php
            // $this->widget('bootstrap.widgets.TbCarousel', array(
              // 'items'=>array(
                  // array(
                            // 'image'=>Yii::app()->baseUrl.'/images/first-image.png',
                            // 'label'=>'Palaver',
                            // 'caption'=>'IRRI plant breeder Glenn Gregorio discusses the merits of a salinity-tolerant variety with
            // a farmer in Bangladesh.'),
                  // array(
                            // 'image'=>Yii::app()->baseUrl.'/images/second-image.png',
                            // 'label'=>'Queens on Parade',
                            // 'caption'=>'Women play a very active role in participatory varietal selection (PVS), a method where
            // farmers themselves select which variety is suitable in their area.'),
                  // array(
                            // 'image'=>Yii::app()->baseUrl.'/images/third-image.png',
                            // 'label'=>'Feeding the Future',
                            // 'caption'=>'A Bangladeshi child is holding up grains of salinity-tolerant rice grown in the field of his
            // homeland.'),
              // ),
            // ));
            
            
            
            ?>
            <!--</div><!--end carousel-->
        <!--</div><!--logo-carousel-->
			
    
    <div style="vertical-align:center" id="log-in-form"><br><br><br>
        <?php if(Yii::app()->user->isGuest){ ?>
        <p style="width: 240px;">Please login with your credentials:</p>
        <div class="form">
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'login-form',
                'enableClientValidation'=>true,
                'clientOptions'=>array(
                        'validateOnSubmit'=>true,
                ),
        ));
        ?>
        <!--<p class="note">Fields with <span class="required">*</span> are required.</p>-->
                <div class="row">
                        <?php //echo $form->labelEx($model,'username'); ?>
                        <?php echo $form->textField($model,'username', array('class' => 'login-textfield', 'style' => 'height: 25px;', 'title'=>'Username')); ?>
                        <?php echo $form->error($model,'username'); ?>
                </div>

                <div class="row">
                        <?php //echo $form->labelEx($model,'password'); ?>
                        <?php echo $form->passwordField($model,'password',array('class' => 'login-textfield', 'style' => 'height: 25px;', 'title'=>'Password')); ?>
                        <?php echo $form->error($model,'password'); ?>
                </div>

                <div class="row rememberMe">
                        <?php echo $form->checkBox($model,'rememberMe'); ?>
                        <?php echo $form->label($model,'rememberMe'); ?>
                        <?php echo $form->error($model,'rememberMe'); ?>
                </div>

                <div class="row buttons">
                        <?php echo CHtml::submitButton('', array('class'=>'login-button')); ?>
                </div>
                <!--<hr style="width: 240px;">--></p>

                <?php $this->endWidget(); ?>
                <?php //Yii::app()->eauth->renderWidget(); ?>
        </div><!-- form -->
         <?php } else { ?>
         <div id="ins">
            <p class="instruction"> More information about the project may be seen in the <a href="http://dev.cropinfo.org/confluence/display/webtools/BIM+Documents" target="_blank">Confluence</a>
            <br/><br/>To submit bug report, new features request or improvements, please click <a href="http://dev.cropinfo.org/jira/secure/CreateIssue!default.jspa" target="_blank">here</a>. </p>
            </div>
         <?php } ?>
         
    </div><!--log-in-form-->
   
    </div>
</div><!--main-login-->
<br><br>
<!--login-page-->
<SCRIPT>
$('input[type="text"]').each(function(){
 
    this.value = $(this).attr('title');
    $(this).addClass('text-label');
 
    $(this).focus(function(){
        if(this.value == $(this).attr('title')) {
            this.value = '';
            $(this).removeClass('text-label');
        }
    });
 
    $(this).blur(function(){
        if(this.value == '') {
            this.value = $(this).attr('title');
            $(this).addClass('text-label');
        }
    });
});
$('input[type="password"]').each(function(){
 
    this.value = $(this).attr('title');
    $(this).addClass('text-label');
 
    $(this).focus(function(){
        if(this.value == $(this).attr('title')) {
            this.value = '';
            $(this).removeClass('text-label');
        }
    });
 
    $(this).blur(function(){
        if(this.value == '') {
            this.value = $(this).attr('title');
            $(this).addClass('text-label');
        }
    });
});
</SCRIPT>
