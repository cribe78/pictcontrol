<?php
?>

<button class="pseq" data-id="1" />Seq. 1</button>
<button class="pseq" data-id="2" />Seq. 2</button>
<button class="pseq" data-id="3" />Seq. 3</button>
<button class="pseq" data-id="4" />Seq. 4</button>
<br>

<?
foreach ($screen_list as $p) {
    include("sub/commontb.php");
}

?>


<div class='ui-widget-header ui-corner-all'>
    <div class='ptoolbarlabel'>Master Volume</div>
    <div class="pslider pcontrol" 
        data-tt='audio-onkyo' data-tn='1'  data-ut='slider' data-ct='range' data-cn='MVL'
        data-min='0' data-max='100'> </div>
    <span class="pcontrol"  data-tt='audio-onkyo' data-tn='1'  
        data-ut='display' data-ct='range' data-cn='MVL'></span> 
</div>
