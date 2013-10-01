<?php
?>

<button class="pseq" data-name="proj-on" />Projectors On</button>
<button class="pseq" data-name="proj-off" />Projectors Off</button>
<button class="pseq" data-name="all-dvi" />All DVI</button>
<button class="pseq" data-name="comp234" />Comp 2-3-4</button>


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
