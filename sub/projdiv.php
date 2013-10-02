<?php
$powr_id = getID();
$shut_id = getID();
$mute_id = getID();
$freeze_id = getID();
$test_id = getID();
$scaling_id = getID();
$input_id = getID();
?>
<div class='ppowrdiv ui-widget-header ui-corner-all'>
    <input type='checkbox' id='<?=$powr_id?>'  class='screen<?=$p?> pcontrol'
    data-cn='POWR' data-tn='<?=$p?>' data-tt='proj' data-ut='checkbox' data-ct='boolean' />
    <label for='<?=$powr_id?>'>Power</label>
    <input type='checkbox' id='<?=$shut_id?>'  class='screen<?=$p?> pcontrol'
    data-cn='SHUT' data-tn='<?=$p?>' data-tt='proj' data-ut='checkbox' data-ct='boolean' />
    <label for='<?=$shut_id?>'>Shutter</label>
    <input type='checkbox' id='<?=$mute_id?>'  class='screen<?=$p?> pcontrol'
    data-cn='PMUT' data-tn='<?=$p?>' data-tt='proj' data-ut='checkbox' data-ct='boolean' />
    <label for='<?=$mute_id?>'>Picture Mute</label>
    <input type='checkbox' id='<?=$freeze_id?>'  class='screen<?=$p?> pcontrol'
    data-cn='FRZE' data-tn='<?=$p?>' data-tt='proj' data-ut='checkbox' data-ct='boolean' />
    <label for='<?=$freeze_id?>'>Freeze</label>
</div>
<div class='ptestdiv ui-widget-header ui-corner-all'>
    <div class='ptoolbarlabel'>Test Patterns:</div>
    <span id="<?=$test_id?>" class="pcontrol"  
        data-ct='range' data-ut='radio' data-cn='TEST' data-tt='proj' data-tn='<?=$p?>'>
<?
radioHTML($test_id, 0, "Off");
radioHTML($test_id, 1, "1");
radioHTML($test_id, 2, "2");
radioHTML($test_id, 3, "3");
radioHTML($test_id, 4, "4");
?>
    </span>
</div>
<div class='pscalingdiv ui-widget-header ui-corner-all'>
    <div class='ptoolbarlabel'>Scaling:</div>
    <span id="<?=$scaling_id?>" class="pcontrol"  
        data-ct='range' data-ut='radio' data-cn='SABS' data-tt='proj' data-tn='<?=$p?>'>
<?
radioHTML($scaling_id, 0, "1:1");
radioHTML($scaling_id, 1, "Fill All");
radioHTML($scaling_id, 2, "Fill Aspect Ratio");
radioHTML($scaling_id, 3, "Fill 16:9");
radioHTML($scaling_id, 4, "Fill 4:3");
radioHTML($scaling_id, 9, "Fill LB to 16:9");
radioHTML($scaling_id, 10, "Fill LB st to 16:9");
?>
    </span>
</div>
<div class='pinputdiv ui-widget-header ui-corner-all'>
    <div class='ptoolbarlabel'>Input:</div>
    <span id="<?=$input_id?>" class="pcontrol"  
        data-ct='range' data-ut='radio' data-cn='IABS' data-tt='proj' data-tn='<?=$p?>'>
<?
radioHTML($input_id, 0, "VGA");
radioHTML($input_id, 1, "BNC");
radioHTML($input_id, 2, "DVI");
radioHTML($input_id, 4, "S-Video");
radioHTML($input_id, 5, "Composite");
radioHTML($input_id, 6, "Component");
radioHTML($input_id, 8, "HDMI");
?>
    </span>
</div>

<div class='pzoomdiv ui-widget-header ui-corner-all'>
    <div class='ptoolbarlabel'>Zoom:</div>

    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='ZOIN' data-value='1'>
    In1</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='ZOIN' data-value='2' >
    In2</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='ZOIN' data-value='3'>
    In3</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='ZOUT' data-value='1'> 
    Out1</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='ZOUT' data-value='2'> 
    Out2</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='ZOUT' data-value='3'>
    Out3</button>
</div>

<div class='pfocusdiv ui-widget-header ui-corner-all'>
    <div class='ptoolbarlabel'>Focus:</div>

    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='FOIN' data-value='1'>
    In1</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='FOIN' data-value='2' >
    In2</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='FOIN' data-value='3'>
    In3</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='FOUT' data-value='1'> 
    Out1</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='FOUT' data-value='2'> 
    Out2</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='FOUT' data-value='3'>
    Out3</button>
</div>

<div class='pshiftupdiv ui-widget-header ui-corner-all'>
    <div class='ptoolbarlabel'>Lens Shift (U/D):</div>

    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='LSUP' data-value='1'>
    Up1</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='LSUP' data-value='2' >
    Up2</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='LSUP' data-value='3'>
    Up3</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='LSDW' data-value='1'> 
    Down1</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='LSDW' data-value='2'> 
    Down2</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='LSDW' data-value='3'>
    Down3</button>
</div>

<div class='pshiftlrdiv ui-widget-header ui-corner-all'>
    <div class='ptoolbarlabel'>Lens Shift (L/R):</div>

    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='LSLF' data-value='1'>
    Left1</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='LSLF' data-value='2' >
    Left2</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='LSLF' data-value='3'>
    Left3</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='LSRH' data-value='1'> 
    Right1</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='LSRH' data-value='2'> 
    Right2</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='LSRH' data-value='3'>
    Right3</button>
</div>

<div class='pirisdiv ui-widget-header ui-corner-all'>
    <div class='ptoolbarlabel'>Iris:</div>

    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='IROP' data-value='1'>
    Open1</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='IROP' data-value='2' >
    Open2</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='IROP' data-value='3'>
    Open3</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='IRCL' data-value='1'> 
    Close1</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='IRCL' data-value='2'> 
    Close2</button>
    <button class='pcontrol' data-tt='proj' data-tn='<?=$p?>'
        data-ut='button' data-ct='stateless' data-cn='IRCL' data-value='3'>
    Close3</button>
</div>


<div class='ui-widget-header ui-corner-all'>
    <div class='ptoolbarlabel'>Brightness</div>
    <div class="pslider pcontrol" 
        data-tt='proj' data-tn='<?=$p?>'  data-ut='slider' data-ct='range' data-cn='BRIG'
        data-min='0' data-max='100'> </div>
    <span class="pcontrol"  data-tt='proj' data-tn='<?=$p?>'  
        data-ut='display' data-ct='range' data-cn='BRIG'></span> 
</div>

<div class='ui-widget-header ui-corner-all'>
    <div class='ptoolbarlabel'>Contrast</div>
    <div class="pslider pcontrol" 
        data-tt='proj' data-tn='<?=$p?>'  data-ut='slider' data-ct='range' data-cn='CNTR'
        data-min='0' data-max='100'> </div>
    <span class="pcontrol"  data-tt='proj' data-tn='<?=$p?>'  
        data-ut='display' data-ct='range' data-cn='CNTR'></span> 
</div>

<div class='ui-widget-header ui-corner-all'>
    <div class='ptoolbarlabel'>Sharpness</div>
    <div class="pslider pcontrol" 
        data-tt='proj' data-tn='<?=$p?>'  data-ut='slider' data-ct='range' data-cn='SHRP'
        data-min='0' data-max='20'> </div>
    <span class="pcontrol"  data-tt='proj' data-tn='<?=$p?>'  
        data-ut='display' data-ct='range' data-cn='SHRP'></span> 
</div>

<div class='pstatsdiv ui-widget-header ui-corner-all'>
    <div class='ptoolbarlabel'>Lamp 1</div>
    <span>Runtime:</span>
    <span class='pcontrol' data-tt='proj' data-tn='<?=$p?>' data-ct='select'
          data-cn='LTR1' data-ut='display'></span>
    <span>Status:</span>
    <span class='pcontrol' data-tt='proj' data-tn='<?=$p?>' data-ct='select'
          data-cn='LST1' data-ut='display'></span>
</div>
