<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Editor';
$this->breadcrumbs=array(
	'Editor',
);

?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl;?>/js/d3.v3.min.js"></script>
<title>Download SVG</title>
<style type="text/css">
    a{
        cursor: pointer;
        text-decoration: underline;
        color: black;
    }
    #download{
        border: 1px solid silver;
        position: absolute;
        opacity: 0;
    }
</style>
</head>
<body>

<div id="viz"></div>
<a href="#" id="generate">Click to save as image</a>

<script type="text/javascript">

// Modified from https://groups.google.com/d/msg/d3-js/aQSWnEDFxIc/k0m0-q-3h1wJ

d3.select("#viz")
    .append("svg:svg")
    .attr("width", 300)
    .attr("height", 200)
    .style("background-color", "WhiteSmoke")
    .append("svg:rect")
    .attr("fill", "aliceblue")
    .attr("stroke", "cadetblue")
    .attr("width", 60)
    .attr("height", 40)
    .attr("x", 50)
    .attr("y", 50);

d3.select("#generate")
    .on("click", writeDownloadLink);

function writeDownloadLink(){
    var html = d3.select("svg")
        .attr("title", "test2")
        .attr("version", 1.1)
        .attr("xmlns", "http://www.w3.org/2000/svg")
        .node().parentNode.innerHTML;

    d3.select("body").append("div")
        .attr("id", "download")
        .style("top", event.clientY+20+"px")
        .style("left", event.clientX+"px")
        .html("Right-click on this preview and choose Save as<br />Left-Click to dismiss<br />")
        .append("img")
        .attr("src", "data:image/svg+xml;base64,"+ btoa(html));

    d3.select("#download")
        .on("click", function(){
            if(event.button == 0){
                d3.select(this).transition()
                    .style("opacity", 0)
                    .remove();
            }
        })
        .transition()
        .duration(500)
        .style("opacity", 1);
};

</script>

</body>
</html>