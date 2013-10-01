<?php 
$keystone_sliders = array("A" => [ "label" => "Top Left H",
                                   "min" => 0,
                                   "max" => 650 ],
                          "B" => [ "label" => "Top Left V",
                                   "min" => 0,
                                   "max" => 650 ],
                          "C" => [ "label" => "Bottom Left H",
                                   "min" => -0,
                                   "max" => 650 ],
                          "D" => [ "label" => "Bottom Left V",
                                   "min" => -650,
                                   "max" => 0 ],
                          "E" => [ "label" => "Top Right H",
                                   "min" => -650,
                                   "max" => 0 ],
                          "F" => [ "label" => "Top Right V",
                                   "min" => 0,
                                   "max" => 650 ],
                          "G" => [ "label" => "Bottom Right H",
                                   "min" => -650,
                                   "max" => 0 ],
                           "H" => [ "label" => "Bottom Right V",
                                    "min" => -650,
                                    "max" => 0 ]);

$s_radio_id = getID();
$test_id = getID();
$scaling_id = getID();
$source_id = getID();
$keystone_id = getID();

?>
<div class='ssourcediv ui-widget-header ui-corner-all'>
    <div class='stoolbarlabel'>Input:</div>
    <span id="<?=$source_id?>" class="pcontrol"  
        data-ct='writeonly' data-ut='radio' data-cn='BA' data-tt='scaler' data-tn='<?=$p?>'>
<?
radioHTML($source_id, 'A', "Component");
radioHTML($source_id, 'B', "VGA");
radioHTML($source_id, 'C', "DVI");
radioHTML($source_id, 'D', "S-Video");
radioHTML($source_id, 'E', "Composite");
radioHTML($source_id, 'F', "SDI");
radioHTML($source_id, 'G', "HDMI");
?>
    </span>
</div>
<div class='sscalingdiv ui-widget-header ui-corner-all'>
    <div class='stoolbarlabel'>Scaling:</div>
    <span id="<?=$scaling_id?>" class="pcontrol"  
        data-ct='writeonly' data-ut='radio' data-cn='AB' data-tt='scaler' data-tn='<?=$p?>'>
<?
radioHTML($scaling_id, 'A', "Standard");
radioHTML($scaling_id, 'B', "Full Screen");
radioHTML($scaling_id, 'C', "Crop");
radioHTML($scaling_id, 'D', "Anamorphic");
radioHTML($scaling_id, 'E', "Flexview");
radioHTML($scaling_id, 'F', "TheatreScope");
radioHTML($scaling_id, 'G', "Squeeze");
?>
    </span>
</div>
<div id='flipheader' class="ui-widget-header" >
    <div class='stoolbarlabel'>Flip/Mirror:</div>
    <span id="<?=$keystone_id?>" class="pcontrol"  
        data-ct='writeonly' data-ut='radio' data-cn='FA' data-tt='scaler' data-tn='<?=$p?>'>
<?
        radioHTML($keystone_id, 'z', "Reset");
        radioHTML($keystone_id, 'I', "Front Tabletop");
        radioHTML($keystone_id, 'J', "Front Ceiling");
        radioHTML($keystone_id, 'K', "Rear Tabletop");
        radioHTML($keystone_id, 'L', "Rear Ceiling");
?>
    </span>
</div>  

<div class='keystonediv ui-widget-content'> 
    <div id='keystoneheader' class="ui-widget-header" >
        <span class='stoolbarlabel'>Keystone Controls</span>
    </div>
    <div class='keystonesliderdiv'>
    <div class='keystonedivleft'>
<?
        foreach (array_keys($keystone_sliders) as $ks) {
            $label = $keystone_sliders[$ks]['label'];
            $id = getID();
            $min = $keystone_sliders[$ks]['min'];
            $max = $keystone_sliders[$ks]['max'];

            if ($ks == "E") {
                echo("</div><div class='keystonedivright'>");
            }
?>
    <span><?=$label?>:</span>
        <span class="pcontrol"  data-tt='scaler' data-tn='<?=$p?>'  
        data-ut='display' data-ct='range' data-cn='FA<?=$ks?>'></span> 
    <div id='<?=$id?>' class="scaler<?=$p?> keystoneslider pcontrol" 
        data-tt='scaler' data-tn='<?=$p?>'  data-ut='slider' data-ct='range' data-cn='FA<?=$ks?>'
        data-min='<?=$min?>' data-max='<?=$max?>'> 
    
    </div>
<?
        }
?>
    </div>
    </div>
</div>


<div class="sposition ui-widget-content" >
    <div class="spositionheader ui-widget-header ui-corner-all">Position Controls</div>
   <span>Vertical:</span> 
    <span class="pcontrol" data-tt='scaler' data-tn='<?=$p?>'
        data-ut='display' data-ct='range' data-cn='ACA'> </span>
    <div class="pcontrol" data-tt='scaler' data-tn='<?=$p?>'
        data-ut='slider' data-ct='range' data-cn='ACA'
        data-min='-1' data-max='100'> </div>
   <span>Horizontal:</span> 
    <span class="pcontrol" data-tt='scaler' data-tn='<?=$p?>'
        data-ut='display' data-ct='range' data-cn='ACB'> </span>
    <div class="pcontrol" data-tt='scaler' data-tn='<?=$p?>'
        data-ut='slider' data-ct='range' data-cn='ACB'
        data-min='-1' data-max='400'> </div>
</div>
<?
    $left_id = getID();
    $right_id = getID();
    $top_id = getID();
    $bottom_id = getID();
?>

<div class="sedges ui-widget-content">
    <div class="sedgesheader ui-widget-header ui-corner-all">Edge Blending</div>
    <input type='checkbox' data-ut='checkbox' data-tt='scaler' data-tn='<?=$p?>'
    data-ct='boolean' data-cn='FFD' id='<?=$top_id?>' class='pcontrol' />
    <label for='<?=$top_id?>'>Top</label> 
    <span class="pcontrol"  data-tt='scaler' data-tn='<?=$p?>'  
        data-ut='display' data-ct='range' data-cn='FFH'></span> 
    <div class="scaler<?=$p?> edgeslider pcontrol" 
        data-tt='scaler' data-tn='<?=$p?>'  data-ut='slider' data-ct='range' data-cn='FFH'
        data-min='0' data-max='511'> </div>
    <input type='checkbox' data-ut='checkbox' data-tt='scaler' data-tn='<?=$p?>'
    data-ct='boolean' data-cn='FFA' id='<?=$bottom_id?>' class='pcontrol' />
    <label for='<?=$bottom_id?>'>Bottom</label> 
    <span class="pcontrol"  data-tt='scaler' data-tn='<?=$p?>'  
        data-ut='display' data-ct='range' data-cn='FFE'></span> 
    <div class="scaler<?=$p?> edgeslider pcontrol" 
        data-tt='scaler' data-tn='<?=$p?>'  data-ut='slider' data-ct='range' data-cn='FFE'
        data-min='0' data-max='511'> </div>
    <input type='checkbox' data-ut='checkbox' data-tt='scaler' data-tn='<?=$p?>'
    data-ct='boolean' data-cn='FFB' id='<?=$left_id?>' class='pcontrol' />
    <label for='<?=$left_id?>'>Left</label> 
    <span class="pcontrol"  data-tt='scaler' data-tn='<?=$p?>'  
        data-ut='display' data-ct='range' data-cn='FFF'></span> 
    <div class="scaler<?=$p?> edgeslider pcontrol" 
        data-tt='scaler' data-tn='<?=$p?>'  data-ut='slider' data-ct='range' data-cn='FFF'
        data-min='0' data-max='511'> </div>
    <input type='checkbox' data-ut='checkbox' data-tt='scaler' data-tn='<?=$p?>'
    data-ct='boolean' data-cn='FFC' id='<?=$right_id?>' class='pcontrol' />
    <label for='<?=$right_id?>'>Right</label> 
    <span class="pcontrol"  data-tt='scaler' data-tn='<?=$p?>'  
        data-ut='display' data-ct='range' data-cn='FFG'></span> 
    <div class="scaler<?=$p?> edgeslider pcontrol" 
        data-tt='scaler' data-tn='<?=$p?>'  data-ut='slider' data-ct='range' data-cn='FFG'
        data-min='0' data-max='511'> </div>
</div>
<div id='s<?=$p?>sourcetb' class="scaler<?=$p?> ui-widget-header">
    <span>Input Res:</span>    
    <span id="s<?=$p?>inputres" data-tt='scaler' data-tn='<?=$p?>' data-ut='display' data-cn='EA'
    data-ct='select' class='pcontrol'> </span>
    <span>Firmware Rev:</span>    
    <span id="s<?=$p?>inputres" data-tt='scaler' data-tn='<?=$p?>' data-ut='display' data-cn='EI'
    data-ct='select' class='pcontrol'> </span>
    <input type='checkbox' id='<?=$test_id?>' class='pcontrol' data-tt='scaler' data-tn='<?=$p?>' data-ut='checkbox'
        data-ct='boolean' data-cn='test' />
    <label for='<?=$test_id?>'>Test Pattern</label>

</div>
