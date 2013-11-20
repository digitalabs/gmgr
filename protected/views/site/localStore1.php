<?php /** @var BootActiveForm $form */

//$this->pageTitle=Yii::app()->name . ' - Importer';
echo "NIKKI"."<br>";
  
?>
<br>
<body onload="applySetting()">
<pre>
<section><form action="" onsubmit="javascript:setSettings()"><label>Select your BG color: </label>
<input id="favcolor" type="color" value="#ffffff" />

<label>Select Font Size: </label>
<input id="fontwt" type="number" max="14" min="10" value="13" />

<input type="submit" value="Save" />
<input onclick="clearSettings()" type="reset" value="Clear" /></form></section>
</pre>
</body>

<script>
function setSettings() {
if ('localStorage' in window && window['localStorage'] !== null) {
   try {
       var favcolor = document.getElementById('favcolor').value;
       var fontwt = document.getElementById('fontwt').value;
       localStorage.setItem('bgcolor', favcolor);
       localStorage.fontweight = fontwt;
   } catch (e) {
       if (e == QUOTA_EXCEEDED_ERR) {
           alert('Quota exceeded!');
       }
   }
   } else {
       alert('Cannot store user preferences as your browser do not support local storage');
   }
}

function applySetting() {
   if (localStorage.length != 0) {
   document.body.style.backgroundColor = localStorage.getItem('bgcolor');
   document.body.style.fontSize = localStorage.fontweight + 'px';
   document.getElementById('favcolor').value = localStorage.bgcolor;
   document.getElementById('fontwt').value = localStorage.fontweight;
   } else {
   document.body.style.backgroundColor = '#FFFFFF';
   document.body.style.fontSize = '13px'
   document.getElementById('favcolor').value = '#FFFFFF';
   document.getElementById('fontwt').value = '13';
   }
}

function clearSettings() {
       localStorage.removeItem("bgcolor");
       localStorage.removeItem("fontweight");
       document.body.style.backgroundColor = '#FFFFFF';
       document.body.style.fontSize = '13px'
       document.getElementById('favcolor').value = '#FFFFFF';
       document.getElementById('fontwt').value = '13';

}

window.addEventListener('storage', storageEventHandler, false);
function storageEventHandler(event) {
       applySetting();
}
</script>