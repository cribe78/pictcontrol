<?
$switcherInputs = array(
        1 => "SAT1",
        2 => "SAT2",
        3 => "SAT3",
        4 => "BD",
        5 => "IN5",
        6 => "IN6",
        7 => "IN7",
        8 => "IN8" );

$commands = array( 
        'scaler' => array(
        'AAA' => array(
            'name' => "Brightness",
            'type' => "range",
            'min' => 0,
            'max' => 100
        ),
        'AAB' => array(
            'name' => "Contrast",
            'type' => "range",
            'min' => 0,
            'max' => 100
        ),
        'AAC' => array(
            'name' => "Sharpness",
            'type' => "range",
            'min' => 0,
            'max' => 100
        ),
        'AAD' => array(
            'name' => "Detail Enhancement",
            'type' => "range",
            'min' => 0,
            'max' => 100
        ),
        'AAE' => array(
            'name' => 'Input Gamma',
            'type' => 'select',
            'readonly' => true,
            'values' => array(
                '0' => '1.0',
                '1' => '1.5',
                '2' => '2.2',
                '3' => '2.4',
                '4' => '2.5',
                '5' => '2.8'
            )
        ),
        'AAF' => array(
            'name' => 'Output Gamma',
            'type' => 'select',
            'readonly' => true,
            'values' => array(
                '0' => '1.0',
                '1' => '2.2',
                '2' => '2.4',
                '3' => '2.5',
                '4' => '2.8'
            )
        ),
        'AAH' => array(
            'name' => 'Color Temp',
            'type' => 'select',
            'readonly' => true,
            'values' => array(
                '0' => '9300k',
                '1' => '6500k',
                '2' => '5500k'
            )
        ),
        'AAI' => array(
            'name' => "Color",
            'type' => "range",
            'min' => 0,
            'max' => 100
        ),
        'AAJ' => array(
            'name' => "Hue",
            'type' => "range",
            'min' => 0,
            'max' => 360
        ),
        'BA' => array(
            'name' => "Input",
            'type' => "select",
            'writeonly'=> true,
            'values' => array(
                'A' => "Component",
                'B' => "VGA",
                'C' => "DVI",
                'D' => "S-Video",
                'E' => "Composite",
                'F' => "SDI",
                'G' => "HDMI" )
        ),
        'AB' => array(
            'name' => "Scaling",
            'type' => "select",
            'writeonly'=> true,
            'values' => array(
                'A' => "Standard",
                'B' => "Full Screen",
                'C' => "Crop",
                'D' => "Anamorphic",
                'E' => "Flexview",
                'F' => "TheatreScope",
                'G' => "Squeeze" )
        ),
        'FA' => array(
            'name' => "Flip/Mirror",
            'type' => "select",
            'writeonly' => true,
            'values' => array(
                'z' => "Reset",
                'I' => "Front Tabletop",
                'J' => "Front Ceiling",
                'K' => "Rear Tabletop",
                'L' => "Rear Ceiling"
            )
        ),
        'FAA' => array(
            'name' => "TL-H",
            'type' => "range",
            'min' => 0,
            'max' => 650
        ),
        'FAB' => array(
            'name' => "TL-V",
            'type' => "range",
            'min' => 0,
            'max' => 650
        ),
        'FAC' => array(
            'name' => "BL-H",
            'type' => "range",
            'min' => 0,
            'max' => 650
        ),
        'FAD' => array(
            'name' => "BL-V",
            'type' => "range",
            'min' => -650,
            'max' => 0
        ),
        'FAE' => array(
            'name' => "TR-H",
            'type' => "range",
            'min' => -650,
            'max' => 0
        ),
        'FAF' => array(
            'name' => "TR-V",
            'type' => "range",
            'min' => 0,
            'max' => 650
        ),
        'FAG' => array(
            'name' => "BR-H",
            'type' => "range",
            'min' => -650,
            'max' => 0
        ),
        'FAH' => array(
            'name' => "BR-V",
            'type' => "range",
            'min' => -650,
            'max' => 0
        ),
        'ACA' => array(
            'name' => "Vertical Pos.",
            'type' => "range",
            'min' => -1,
            'max' => 400
        ),
        'ACB' => array(
            'name' => "Horizontal Pos.",
            'type' => "range",
            'min' => -1280,
            'max' => 1280
        ),
        'FFA' => array(
            'name' => "Edge Blend Bottom On/Off",
            'type' => "boolean"
        ),
        'FFB' => array(
            'name' => "Edge Blend Left On/Off",
            'type' => "boolean"
        ),
        'FFC' => array(
            'name' => "Edge Blend Right On/Off",
            'type' => "booolean"
        ),
        'FFD' => array(
            'name' => "Edge Blend Top On/Off",
            'type' => "boolean"
        ),
        'FFE' => array(
            'name' => "Size",
            'type' => "range",
            'min' => 0,
            'max' => 511
        ),
        'FFF' => array(
            'name' => "Size",
            'type' => "range",
            'min' => 0,
            'max' => 511
        ),
        'FFG' => array(
            'name' => "Size",
            'type' => "range",
            'min' => 0,
            'max' => 511
        ),
        'FFH' => array(
            'name' => "Size",
            'type' => "range",
            'min' => 0,
            'max' => 511
        ),
        'EA' => array(
            'name' => "Input Res",
            'type' => "text",
            'readonly' => true
        ),
        'EI' => array(
            'name' => "Firmware Rev.",
            'type' => "text",
            'readonly' => true
        ),
        'CB' => array( 
            'name' => "Test Pattern",
            'type' => "select",
            'writeonly' => true,
            'values' => array(
                "'z'" => "Off",
                "'A0'" => "1",
                "'A1'" => "2",
                "'A2'" => "3",
                "'A3'" => "4"
            )
        )
    ),
    'proj' => array(
        'BRIG' => array(
            'name' => "Brightness",
            'type' => "range",
            'min' => 0,
            'max' => 100
        ),
        'CNTR' => array(
            'name' => "Contrast",
            'type' => "range",
            'min' => 0,
            'max' => 100
        ),
        'FOIN' => array(
            'name' => "Focus",
            'type' => "select",
            'writeonly' => true,
            'values' => array(
                '1' => "In 1",
                '2' => "In 2",
                '3' => "In 3"
            )
        ),
        'FOUT' => array(
            'name' => "Focus",
            'type' => "select",
            'writeonly' => true,
            'values' => array(
                '1' => "Out 1",
                '2' => "Out 2",
                '3' => "Out 3"
            )
        ),
        'FRZE' => array(
            'name' => "Freeze",
            'type' => "boolean"
        ),
        'GABS' => array(
            'name' => "Gamma",
            'type' => "select",
            'values' => array(
                '0' => 'Film1',
                '1' => 'Film2',
                '2' => 'Video1',
                '3' => 'Video2',
                '7' => 'Computer1',
                '8' => 'COmputer2'
            )
        ),
        'IABS' => array(
            'name' => "Input", 
            'type' => "select",
            'values' => array(
                '0' => "VGA",
                '1' => "BNC",
                '2' => "DVI",
                '4' => "S-Video",
                '5' => "Composite",
                '6' => "Component",
                '8' => "HDMI"
            )
        ),
        'IRCL' => array(
            'name' => "Iris",
            'type' => "select",
            'writeonly' => true,
            'values' => array(
                '1' => "Close 1",
                '2' => "Close 2",
                '3' => "Close 3"
            )
        ),
        'IROP' => array(
            'name' => "Iris",
            'type' => "select",
            'writeonly' => true,
            'values' => array(
                '1' => "Open 1",
                '2' => "Open 2",
                '3' => "Open 3"
            )
        ),
        'LSDW' => array(
            'name' => "Lens Shift",
            'type' => "select",
            'writeonly' => true,
            'values' => array(
                '1' => "Down 1",
                '2' => "Down 2",
                '3' => "Down 3"
            )
        ),
        'LSLF' => array(
            'name' => "Lens Shift",
            'type' => "select",
            'writeonly' => true,
            'values' => array(
                '1' => "Left 1",
                '2' => "Left 2",
                '3' => "Left 3"
            )
        ),
        'LSRH' => array(
            'name' => "Lens Shift",
            'type' => "select",
            'writeonly' => true,
            'values' => array(
                '1' => "Right 1",
                '2' => "Right 2",
                '3' => "Right 3"
            )
        ),
        'LSUP' => array(
            'name' => "Lens Shift",
            'type' => "select",
            'writeonly' => true,
            'values' => array(
                '1' => "Up 1",
                '2' => "Up 2",
                '3' => "Up 3"
            )
        ),
        'PMUT' => array(
            'name' => "Picture Mute",
            'type' => "boolean"
        ),
        'POWR' => array(
            'name' => "Power",
            'type' => "boolean"
        ),
        'POST' => array(
            'name' => "State",
            'type' => "select",
            'readonly' => true,
            'values' => array(
                '0' => "Deep Sleep",
                '1' => "Off",
                '2' => "Powering up",
                '3' => "On",
                '4' => "Powering down",
                '5' => "Critical powering down",
                '6' => "Critical off"
            )
        ),
        'SABS' => array(
            'name' => "Scaling",
            'type' => "select",
            'values' => array(
                '0' => "1:1",
                '1' => "Fill All",
                '2' => "Fill Aspect Ratio",
                '3' => "Fill 16:9",
                '4' => "Fill 4:3",
                '9' => "Fill LB to 16:9"
            )
        ),
        'SHUT' => array(
            'name' => "Shutter",
            'type' => "boolean"
        ),
        'TEST' => array(
            'name' => "Test Pattern",
            'type' => "select",
            'values' => array(
                '0' => "Off",
                '1' => "1",
                '2' => "2",
                '3' => "3",
                '4' => "4"
            )
        ),
        'ZOIN' => array(
            'name' => "Zoom",
            'type' => "select",
            'writeonly' => true,
            'values' => array(
                '1' => "In 1",
                '2' => "In 2",
                '3' => "In 3"
            )
        ),
        'ZOUT' => array(
            'name' => "Zoom",
            'type' => "select",
            'writeonly' => true,
            'values' => array(
                '1' => "Out 1",
                '2' => "Out 2",
                '3' => "Out 3"
            )
        ),
        'SHRP' => array(
            'name' => "Sharpness",
            'type' => "range",
            'min' => 0,
            'max' => 20
        ),
        'LTR1' => array(
            'name' => "Runtime",
            'type' => "text",
            'readonly' => true
        ),
        'LTR2' => array(
            'name' => "Runtime",
            'type' => "text",
            'readonly' => true
        ),
        'UTOT' => array(
            'name' => "Unit Total Time",
            'type' => "text",
            'readonly' => true
        ),
        'LST1' => array( 
            'name' => "Status",
            'type' => "select",
            'readonly' => true,
            'values' => array(
                '0' => "Broken",
                '1' => "Warming up",
                '2' => "On",
                '3' => "Off",
                '4' => "Cooling down",
                '5' => "Missing"
            )
        ),
        'LST2' => array( 
            'name' => "Status",
            'type' => "select",
            'readonly' => true,
            'values' => array(
                '0' => "Broken",
                '1' => "Warming up",
                '2' => "On",
                '3' => "Off",
                '4' => "Cooling down",
                '5' => "Missing"
            )
        ),
        'LMOD' => array(
            'name' => "Lamp Mode",
            'type' => "select",
            'values' => array(
                '0' => "Lamp 1",
                '1' => "Lamp 2",
                '2' => "Dual Lamps",
                '3' => "Auto-switch"
            )
        ),
        'GABS' => array(
            'name' => "Gamma",
            'type' => "select",
            'values' => array(
                '0' => "Film 1",
                '1' => "Film 2",
                '2' => "Video 1",
                '3' => "Video 2",
                '7' => "Computer 1",
                '8' => "Computer 2"
            )
        )
    ),
    "audio-onkyo" => array(
        'MVL' => array(
            'name' => "Master Volume",
            'type' => "range",
            'min' => 0,
            'max' => 100 
        ),
        // Values for this devices are converted to HEX by the control daemon
        'SLI' => array(
            'name' => "Input Selector",
            'type' => "select",
            'values' => array(
                '16' => 'Argos (DVD)',   // 10 per docs
                '48' => '5.1 (Multi Ch.)'  // 30 per docs
            )
        )
    ),
    "audio-onkyo-eth" => array(
        'MVL' => array(
            'name' => "Master Volume",
            'type' => "range",
            'min' => 0,
            'max' => 80
        ),
        // Values for this devices are converted to HEX by the control daemon
        'SLI' => array(
            'name' => "Input Selector",
            'type' => "select",
            'values' => array(
                '1' => 'CBL/SAT',   // 01 per docs
                '2' => 'Game',  // 02 per docs
                '3' => 'Aux',
                '43' => 'Network'   // 2B per docs
            )
        )
    ),
    "switcher-dxp" => array(
        'Out1Vid' => array(
            'name' => "PICT1",
            'type' => "select",
            'values' => $switcherInputs
        ),
        'Out2Vid' => array(
            'name' => "PICT2",
            'type' => "select",
            'values' => $switcherInputs
        ),
        'Out3Vid' => array(
            'name' => "PICT3",
            'type' => "select",
            'values' => $switcherInputs
        ),
        'Out4Vid' => array(
            'name' => "PICT4",
            'type' => "select",
            'values' => $switcherInputs
        ),
        'Out5Vid' => array(
            'name' => "PICT5",
            'type' => "select",
            'values' => $switcherInputs
        ),
        'Out6Vid' => array(
            'name' => "MR",
            'type' => "select",
            'values' => $switcherInputs
        ),
        'Out7Vid' => array(
            'name' => "OUT7",
            'type' => "select",
            'values' => $switcherInputs
        ),
        'Out8Vid' => array(
            'name' => "OUT8",
            'type' => "select",
            'values' => $switcherInputs
        ),  
        'Out1Aud' => array(
            'name' => "PICT1",
            'type' => "select",
            'values' => $switcherInputs
        ),
        'Out2Aud' => array(
            'name' => "PICT2",
            'type' => "select",
            'values' => $switcherInputs
        ),
        'Out3Aud' => array(
            'name' => "PICT3",
            'type' => "select",
            'values' => $switcherInputs
        ),
        'Out4Aud' => array(
            'name' => "PICT4",
            'type' => "select",
            'values' => $switcherInputs
        ),
        'Out5Aud' => array(
            'name' => "PICT5",
            'type' => "select",
            'values' => $switcherInputs
        ),
        'Out6Aud' => array(
            'name' => "MR",
            'type' => "select",
            'values' => $switcherInputs
        ),
        'Out7Aud' => array(
            'name' => "OUT7",
            'type' => "select",
            'values' => $switcherInputs
        ),
        'Out8Aud' => array(
            'name' => "OUT8",
            'type' => "select",
            'values' => $switcherInputs
        )
    ),
    'delay' => array(
        'delay' => array(
            'name' => "us delay",
            'type' => "range",
            'writeonly' => true,
            'min' => 0,
            'max' => 10000000
        )
    ),

    'proj-benq' => array(
        'POW' => array(
            'name' => 'Power',
            'type' => 'boolean'
        )
    )
);


$commands['xap800'] = array();

// Generate xap800 crosspoint matrix
for ($i = 1; $i <= 12; $i++) {
    for ($j = 1; $j <= 12; $j++) {
        $commands['xap800']["MTRXLVL $i I $j O"] = array(
            'name' => "I{$i}O{$j}",
            'type' => 'range',
            'min' => -60,
            'max' => 0
        );

        $commands['xap800']["MTRX $i I $j O"] = array(
            'name' => "I{$i}O{$j}",
            'type' => 'boolean'
        );
    }
}

