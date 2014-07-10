<?php



?>

<div class='ui-widget-header ui-corner-all'>
    <div class='ptoolbarlabel'>Master Volume</div>
    <div class="pslider pcontrol" 
        data-tt='audio-onkyo' data-tn='1'  data-ut='slider' data-ct='range' data-cn='MVL'
        data-min='0' data-max='100'> </div>
    <span class="pcontrol"  data-tt='audio-onkyo' data-tn='1'  
        data-ut='display' data-ct='range' data-cn='MVL'></span> 
</div>

<?
radioBar("audio-onkyo", 1, 'SLI');
