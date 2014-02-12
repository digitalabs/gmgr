<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Editor';
$this->breadcrumbs = array(
    'Editor',
);
Yii::import("ext.graphviz.components.*");
Yii::import("ext.graphviz.widgets.*");
Yii::import('application.modules.curl');
Yii::import('application.modules.file_toArray');

if (isset($_GET['searchBtn'])) {
    //foo();
    echo "Success";
}
?>

<html>
    <head>
    <body>
        <meta charset="utf-8">
        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/editor.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" />

        <br><br> 
        <div style="text-align:right;vertical-align:right;margin-left:10px;margin-right: 10px;">       <br>

                    <!--<strong><small>Search Germplasm</small></strong>&nbsp;-->
            <div style="z-index:4;position:fixed;top:75px;right:40px" class="input-append">    
                <!--<div class="btn-group" data-toggle="buttons-checkbox">
                    <button type="button" class="btn">Name</button>
                    <button type="button" class="btn">GID</button>    
                </div>-->
                <form action="index.php?r=site/editor" method="post">
                    <input disabled="true" title="This feature is a work in progress" style="width:155px;height:30px;" class="span2" id="inputGID" name="inputGID" type="text" placeholder="Search by GID">
                    <button disabled="true" name="searchBtn" id="searchBtn" class="btn btn-primary" onclick="clik()" type="submit">GO</button>
            </div>  

        </div>
        <span id="ajax-loading-indicator">
        </span>

        <!--bg while loading indicator is on-->
        <div style="z-index:1100;" id='screen'>

        </div>

        <!-- <div style="vertical-align:middle;margin-left:5px;margin-right: 5px;">
             <div class="well" style="position:relative;border:1px solid; padding: 0px; height:670px">
                 <div id="graphDiv2" style="position:absolute;top:0px;right:0px;left:202px;overflow-x:scroll;overflow-y:scroll;padding: 0px; text-align: right; vertical-align: right; width: 910px; height: 630px;vertical-align:right;">
                                     <!--<svg style="width:500px;height:800px;overflow-x:hidden;overflow-y:hidden;position:absolute;z-index:2;">
                                                     <g id="vis"></g>
                                     </svg>
                                             <div id="graphDiv" width="5000" style="width:2000px;"></div>
                                     </div> -->
        <div id="graph" style="height: auto !important;width: auto;min-height:30px;">
            <svg width="3500" height="5000" style="height: auto;width: auto;" id="graphDiv"></svg>
        </div>

        <!--<div id="graphDiv" style="z-index:50;">-->
        <!--<a href="#" id="generate">Generate download preview</a>	-->
    </div>
    <div class="div-gradient" style="z-index:0;padding-left: 0px; width: 200px;height:730px;text-align: justify;position:fixed;right:40px;top:115px;bottom:20px;
         -webkit-box-shadow: 3px 3px 16px rgba(50, 50, 50, 0.75);
         -moz-box-shadow:    3px 3px 16px rgba(50, 50, 50, 0.75);
         box-shadow:         3px 3px 16px rgba(50, 50, 50, 0.75);">

        <!--<div style="padding-left:5px;padding-right:5px;"><hr></div>-->
        <br>
        <div class="form-horizontal" style="padding-left: 15px;">
            <input disabled="true" title="This feature is a work in progress" placeholder="All" style="width:50px;height:30px;" value=" " id="maxStep" type="number" name="maxStep" min="1" max="100"> 

            <small><a data-toggle="tooltip" title="By default, a regular pedigree for a particular germplasm is created up to the certain number of known parents. You can, however, choose to show a smaller number of parental generations (steps), or to choose all." data-placement="right">Maximum Steps</a></small>
        </div>
        <div style="margin-left:20px;">
           <small><input disabled="true" class="checkbox" title="This feature is a work in progress" type="checkbox" id="cbox" name="cbox">
                <a data-placement="right" data-toggle="tooltip" title="Derivative and maintenance steps will be included in the pedigree.">Show Selection History</a></input></small><br>
<!--<small><input title="This feature is a work in progress" type="checkbox"><a data-placement="right" data-toggle="tooltip" title="The pedigree graph can label its edges by the name of germplasm methods.">Show Method</a></input></small>-->
        </div>
    </form>  
    <div style="padding-left: 5px;padding-right: 5px; text-align: right;">
        <!--<button name="updateBtn" id="updateBtn" type="submit" class="btn btn-mini btn-primary" onclick="graph2b();">Update</button></form>
        <button title="This feature is a work in progress" class="btn btn-mini btn-success" id="generate" value="" >Save image</button>-->
        <form method="POST" enctype="multipart/form-data" action="<?php echo Yii::app()->baseUrl; ?>/save.php" id="myForm">
            <input type="hidden" name="img_val" id="img_val" value="" />
        </form>

        <div style="padding-left:5px;padding-right:5px;"><hr></div>
        <center>
            <div style="padding-left:5px;padding-right:5px;">Basic Information
                <!--<button data-toggle="modal" data-target="#myModal" onclick="info()" style="width:50px" name="showMore" id="showMore" class="btn btn-primary" onclick="validate()" type="submit">Edit</button>-->
        </center> 

        <br>

        <!--<div style="position:fixed;top:20px;left:40px;padding:5px;">
                <button title="This feature is a work in progress" class="btn btn-mini btn-success" id="savePNG" value="">Edit</button>
        </div>-->

        <small>
            <div style="padding-left:5px;padding-right:5px;"> 

                <form action="index.php?r=site/editor" method="post">
                    <table style="border-collapse: separate !important;border-radius: 6px 6px 6px 6px;
                           -moz-border-radius: 6px 6px 6px 6px;
                           -webkit-border-radius: 6px 6px 6px 6px;
                           box-shadow: 0 1px 1px #CCCCCC;

                           " class="table table-hover table-condensed">
                        <tr><td width="50px" bgcolor="#0080FF" style="color: white;"><small>GID</td>
                            <td name="gid" id="gid"  align="left" bgcolor="white" style=" vertical-align: left; text-align: left;">
                            </td><input type="hidden" id="hidGID" name="hidGID"></tr>
                        <tr><td bgcolor="#0080FF" style="color: white;"><small>Name</td>
                            <td id="gname" bgcolor="white" style=" vertical-align: left; text-align: left;">
                            </td><input type="hidden" id="hidname" name="hidname"></tr>
                        <tr><td bgcolor="#0080FF" style="color: white;"><small>Method</td>
                            <td id="gmethod" bgcolor="white" style=" vertical-align: left; text-align: left;">
                            </td><input type="hidden" id="hidmethod" name="hidGID"></tr>
                        <tr><td bgcolor="#0080FF" style="color: white;"><small>Method Type</td>
                            <td id="gmtype" bgcolor="white" style=" vertical-align: left; text-align: left;">
                            </td><input type="hidden" id="hidmtype" name="hidGID"></tr>
                        <tr><td bgcolor="#0080FF" style="color: white;"><small>Date</td>
                            <td id="gdate" bgcolor="white" style=" vertical-align: left; text-align: left;">
                            </td><input type="hidden" id="hiddate" name="hidGID"></tr>
                        <tr><td bgcolor="#0080FF" style="color: white;"><small>Country</td>
                            <td id="gcountry" bgcolor="white" style=" vertical-align: left; text-align: left;">
                            </td><input type="hidden" id="hidcountry" name="hidGID"></tr>
                        <tr><td bgcolor="#0080FF" style="color: white;"><small>Location</td>
                            <td id="gloc" bgcolor="white" style=" vertical-align: left; text-align: left;">
                            </td><input type="hidden" id="hidloc" name="hidloc"></tr>
                        <tr><td bgcolor="#0080FF" style="color: white;"><small>Crop Name</td>
                            <td id="gcname" bgcolor="white" style=" vertical-align: left; text-align: left;">
                            </td><input type="hidden" id="hidcname" name="hidcname"></tr>
                        <tr><td bgcolor="#0080FF" style="color: white;"><small>Reference</td>
                            <td id="gref" bgcolor="white" style=" vertical-align: left; text-align: left;">
                            </td><input type="hidden" id="hidref" name="hidref"></tr>
                        <tr><td bgcolor="#0080FF" style="color: white;"><small>GPID1</td>
                            <td id="gpid1" bgcolor="white" style=" vertical-align: left; text-align: left;">
                            </td><input type="hidden" id="hidgpid1" name="hidgpid1"></tr>
                        <tr><td bgcolor="#0080FF" style="color: white;"><small>GPID2</td>
                            <td id="gpid2" bgcolor="white" style=" vertical-align: left; text-align: left;">
                            </td><input type="hidden" id="hidgpid2" name="hidgpid2"></tr>
                    </table>


                    <div style="position:fixed;bottom:80px;vertical-align:top;text-align: left">

                    </div> 
                </form>

                <div style="position:fixed;bottom:30px;vertical-align:top;text-align: left"><a style="color: white; text-decoration: none;"><img src='images/legend.gif' width="185px" height="100px"></a></div> 
            </div>   
    </div>  



    <div style="vertical-align:right;text-align: right;">

        <p style="position:fixed;bottom:0px;left:50px;padding:5px;"><span class="label label-warning">Note</span> 
            <small>Apply the <i>changes</i> made by clicking the <b>GO</b> button.
                Click node to view germplasm information.</small>
            </span>
    </div>    

    <div id="opener" style="position:fixed; bottom:60px; left:50px">
        <a href="#1" name="1" onclick="show();">View Names    </a>
    </div>
    <div id="opener2" style="position:fixed; bottom:60px; left:130px">
        <a href="#2" name="2" onclick="show2();">View Attributes</a>
    </div>
    <div id="benefits" style="position:fixed; bottom:80px; left:50px; display:none;">
        <b>&nbsp;Alternate Names</b>
        <table style="background-color:white;margin:5px;width:800px;" width="1000px" class="table table-hover table-condensed">
            <tr>
                <th height="10" bgcolor="lightgreen">Name Type</th>
                <th height="10" bgcolor="lightblue">Name</th>
                <th height="10" bgcolor="lightblue">Location</th>
                <th height="10" bgcolor="lightblue">Status</th>
                <th height="10" bgcolor="lightblue">Date</th>
            </tr>
            <tr style="border:1px solid gray">
                <td id="nt1"></td>
                <td id="n1"></td>
                <td id="l1"></td>
                <td id="ns1"></td>
                <td id="d1"></td>
            </tr>
            <tr style="border:1px solid gray">
                <td id="nt2"></td>
                <td id="n2"></td>
                <td id="l2"></td>
                <td id="ns2"></td>
                <td id="d2"></td>
            </tr>
            <tr style="border:1px solid gray">
                <td id="nt3"></td>
                <td id="n3"></td>
                <td id="l3"></td>
                <td id="ns3"></td>
                <td id="d3"></td>
            </tr>
        </table>
        <!--<div id="upbutton"><a onclick="conceal();">&nbsp;Hide</a></div>-->
    </div>
    <div id="benefits2" style="position:fixed; bottom:80px; left:50px; display:none;">
        <b>&nbsp;Attributes</b>
        <table style="margin:5px;width:700px;">
            <tr>
                <th bgcolor="lightblue">Name</th>
                <th bgcolor="lightblue">Description</th>
                <th bgcolor="lightblue">Value</th>
                <th bgcolor="lightblue">Date</th>
            </tr>
            <tr style="border:1px solid gray">
                <td id="an1"></td>
                <td id="ad1"></td>
                <td id="av1"></td>
                <td id="adt1"></td>
            </tr>
            <tr style="border:1px solid gray">
                <td id="an2"></td>
                <td id="ad2"></td>
                <td id="av2"></td>
                <td id="adt2"></td>
            </tr>
            <tr style="border:1px solid gray">
                <td id="an3"></td>
                <td id="ad3"></td>
                <td id="av3"></td>
                <td id="adt3"></td>
            </tr>
        </table>
        <!--<div id="upbutton2"><a onclick="conceal2();">&nbsp;Hide</a></div>-->
    </div>

</div>
<div style="position:fixed;left:40px;top:80px;">
        <!--<span>Zoom</span>-->
    <select class="selectpicker" style="width:80px" width="50px" id='zooming' onchange="zoomings(this);">
        <option value="100%"  selected="selected">Zoom</option>
        <option value="100%">------------</option>
        <option value="25%">25%</option>
        <option value="50%">50%</option>
        <option value="75%">75%</option>
        <option value="100%">100%</option>
        <option value="150%">150%</option>
        <option value="200%">200%</option>
        <option value="250%">250%</option>
        <option value="300%">300%</option>
    </select>
    <!--<button class="btn btn-mini btn-success" id="generate" value="">Save as PNG</button>
    <a href="#" id="generate">Download preview</a>-->
</div> 
<!--</div>-->
<br><br><br><br><br><br>
<!-- end editor content -->

<?php
echo CHtml::hiddenField('local_db_name', '');
echo CHtml::hiddenField('local_db_port', '');
echo CHtml::hiddenField('local_db_username', '');
echo CHtml::hiddenField('central_db_name', '');
echo CHtml::hiddenField('central_db_port', '');
echo CHtml::hiddenField('central_db_username', '');
?>

<script src='<?php echo Yii::app()->baseUrl; ?>/js/jquery.storage.js'></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/d3.v3.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/diagram.js"></script>
        <!--<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/editor5b.js"></script>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/prettify/188.0.0/prettify.js"></script>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/html2canvas.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/jquery.plugin.html2canvas.js"></script>
<script src="<?php echo Yii::app()->baseUrl; ?>/js/vkbeautify.0.99.00.beta.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/rgbcolor.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/canvg.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/svgenie.js"></script>
<script type="text/javascript">

    function Scrolldown() {
    window.scroll(0,2760); 
    }

    window.onload = Scrolldown;

    document.getElementById('inputGID').value = $.localStorage('GID');
    document.getElementById('maxStep').value = $.localStorage('level');

    function info()
    {
    //document.getElementById('egid').value = document.getElementById('gid').value;
    $.localStorage('EGID', jQuery("input#hidGID").val());
    $.localStorage('NAME', jQuery("input#hidname").val());
    $.localStorage('METHOD', jQuery("input#hidmethod").val());
    $.localStorage('MTYPE', jQuery("input#hidmtype").val());
    $.localStorage('DATE', jQuery("input#hiddate").val());
    $.localStorage('CTY', jQuery("input#hidcountry").val());
    $.localStorage('LOC', jQuery("input#hidloc").val());
    $.localStorage('CNAME', jQuery("input#hidcname").val());
    $.localStorage('REF', jQuery("input#hidref").val());
    $.localStorage('GPID1', jQuery("input#hidgpid1").val());
    $.localStorage('GPID2', jQuery("input#hidgpid2").val());

    document.getElementById('egid').value = $.localStorage('EGID');
    document.getElementById('ename').value = $.localStorage('NAME');
    document.getElementById('emethod').value = $.localStorage('METHOD');
    document.getElementById('emtype').value = $.localStorage('MTYPE');
    document.getElementById('edate').value = $.localStorage('DATE');
    document.getElementById('ecountry').value = $.localStorage('CTY');
    document.getElementById('eloc').value = $.localStorage('LOC');
    document.getElementById('ecname').value = $.localStorage('CNAME');
    document.getElementById('eref').value = $.localStorage('REF');
    document.getElementById('egpid1').value = $.localStorage('GPID1');
    document.getElementById('egpid2').value = $.localStorage('GPID2');
    }

    function clik()
    {
    $.localStorage('GID', jQuery("input#inputGID").val());
    $.localStorage('level', jQuery("input#maxStep").val());

    //******get database settings
    document.getElementById('local_db_name').value = localStorage.local_database_name;
    document.getElementById('local_db_port').value = localStorage.local_database_port;
    document.getElementById('local_db_username').value = localStorage.local_database_username;
    document.getElementById('central_db_name').value = localStorage.central_database_name;
    document.getElementById('central_db_port').value = localStorage.central_database_port;
    document.getElementById('central_db_username').value = localStorage.central_database_username;
    }

    function conceal() {      
    if(document.getElementById('benefits').style.display=='block') {
    document.getElementById('benefits').style.display='none';
    document.getElementById('opener').style.display='block';
    }
    }  

    function conceal2() {      
    if(document.getElementById('benefits2').style.display=='block') {
    document.getElementById('benefits2').style.display='none';
    document.getElementById('opener2').style.display='block';
    }
    }

    function show() {
    document.getElementById('benefits2').style.display='none';
    document.getElementById('opener2').style.display='block';
    if(document.getElementById('benefits').style.display=='none') {
    document.getElementById('benefits').style.display='block';
    document.getElementById('opener').style.display='none';
    }
    }

    function show2() {
    document.getElementById('benefits').style.display='none';
    document.getElementById('opener').style.display='block';
    if(document.getElementById('benefits2').style.display=='none') {
    document.getElementById('benefits2').style.display='block';
    document.getElementById('opener2').style.display='none';
    }
    }

    function capture() {
    $('#graph').html2canvas({
    onrendered: function (svg) {
    //Set hidden field's value to image data (base-64 string)
    $('#img_val').val(svg.toDataURL("image/png"));
    //Submit the form manually
    document.getElementById("myForm").submit();
    }
    });
    } 

    function validate()
    {
    var tmp = document.getElementById('maxStep').value;
    var tmp2 = document.getElementById('inputGID').value;
    
    }
   
    window.onclick = function(){
    svgenie.save( document.getElementById('graphDiv'), { name:"this.png" } ); 
    }

    function zoomings(optionSel)
    {
    var OptionSelected = optionSel.selectedIndex;
    var val = optionSel.options[OptionSelected].text;
    var div = document.getElementById ("graphDiv");
    div.style.zoom = val;
    }

    $(document).ready(function() {
    var pop = function(){
    $('#screen').css({ opacity: 0.4, 'width':$(document).width(),'height':$(document).height()});
    $('body').css({'overflow':'hidden'});
    $('#ajax-loading-indicator').css({'display': 'block'});
    }
    $('#searchBtn').click(pop);
    $('#updateBtn').click(pop);
    $('#save').click(pop);

    });


<?php
Yii::app()->clientScript->registerScript(
        'myHideEffect', '$(".info").animate({opacity: 1.0}, 3000).fadeOut("slow");', CClientScript::POS_READY
);
?>


</script>

</body>

</html>
