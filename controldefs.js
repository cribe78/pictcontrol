var commands = { 
    scaler : {
        BA : {
            name : "Input",
            type : "select",
            writeonly: true,
            values : {
                A : "Component",
                B : "VGA",
                C : "DVI",
                D : "S-Video",
                E : "Composite",
                F : "SDI",
                G : "HDMI" }
        },
        AB : {
            name : "Scaling",
            type : "select",
            writeonly: true,
            values : {
                A : "Standard",
                B : "Full Screen",
                C : "Crop",
                D : "Anamorphic",
                E : "Flexview",
                F : "TheatreScope",
                G : "Squeeze" }
        },
        FA : {
            name : "Flip/Mirror",
            type : "select",
            writeonly : true,
            values : {
                z : "Reset",
                I : "Front Tabletop",
                J : "Front Ceiling",
                K : "Rear Tabletop",
                L : "Rear Ceiling"
            }
        },
        FAA : {
            name : "Keystone TL-H",
            type : "range",
            min : 0,
            max : 650
        },
        FAB : {
            name : "Keystone TL-V",
            type : "range",
            min : 0,
            max : 650
        },
        FAC : {
            name : "Keystone BL-H",
            type : "range",
            min : 0,
            max : 650
        },
        FAD : {
            name : "Keystone BL-V",
            type : "range",
            min : -650,
            max : 0
        },
        FAE : {
            name : "Keystone TR-H",
            type : "range",
            min : -650,
            max : 0
        },
        FAF : {
            name : "Keystone TR-V",
            type : "range",
            min : 0,
            max : 650
        },
        FAG : {
            name : "Keystone BR-H",
            type : "range",
            min : -650,
            max : 0
        },
        FAH : {
            name : "Keystone BR-V",
            type : "range",
            min : -650,
            max : 0
        },
        ACA : {
            name : "Vertical Pos.",
            type : "range",
            min : -1,
            max : 400
        },
        ACB : {
            name : "Horizontal Pos.",
            type : "range",
            min : -1,
            max : 400
        },
        FFA : {
            name : "Edge Blend Bottom On/Off",
            type : "boolean"
        },
        FFB : {
            name : "Edge Blend Left On/Off",
            type : "boolean"
        },
        FFC : {
            name : "Edge Blend Right On/Off",
            type : "booolean"
        },
        FFD : {
            name : "Edge Blend Top On/Off",
            type : "boolean"
        },
        FFE : {
            name : "Edge Blend Bottom Size",
            type : "range",
            min : 0,
            max : 511
        },
        FFF : {
            name : "Edge Blend Left Size",
            type : "range",
            min : 0,
            max : 511
        },
        FFG : {
            name : "Edge Blend Right Size",
            type : "range",
            min : 0,
            max : 511
        },
        FFH : {
            name : "Edge Blend Top Size",
            type : "range",
            min : 0,
            max : 511
        },
        EA : {
            name : "Input Res",
            type : "text",
            readonly : true
        },
        EI : {
            name : "Firmware Rev.",
            type : "text",
            readonly : true
        },
        CB : { 
            name : "Test Pattern",
            type : "select",
            writeonly : true,
            values : {
                "z" : "Off",
                "A0" : "1",
                "A1" : "2",
                "A2" : "3",
                "A3" : "4"
            }
        }
    },
    proj : {
        POWR : {
            name : "Power",
            type : "boolean"
        },
        POST : {
            name : "State",
            type : "select",
            readonly : true,
            values : {
                0 : "Deep Sleep",
                1 : "Off",
                2 : "Powering up",
                3 : "On",
                4 : "Powering down",
                5 : "Critical powering down",
                6 : "Critical off"
            }
        },
        SHUT : {
            name : "Shutter",
            type : "boolean"
        },
        PMUT : {
            name : "Picture Mute",
            type : "boolean"
        },
        FRZE : {
            name : "Freeze",
            type : "boolean"
        },
        TEST : {
            name : "Test Pattern",
            type : "select",
            values : {
                0 : "Off",
                1 : "1",
                2 : "2",
                3 : "3",
                4 : "4"
            }
        },
        SABS : {
            name : "Scaling",
            type : "select",
            values : {
                0 : "1:1",
                1 : "Fill All",
                2 : "Fill Aspect Ratio",
                3 : "Fill 16:9",
                4 : "Fill 4:3",
                9 : "Fill LB to 16:9"
            }
        },
        IABS : {
            name : "Input", 
            type : "select",
            values : {
                0 : "VGA",
                1 : "BNC",
                2 : "DVI",
                4 : "S-Video",
                5 : "Composite",
                6 : "Component",
                8 : "HDMI"
            }
        },
        ZOIN : {
            name : "Zoom",
            type : "select",
            writeonly : true,
            values : {
                1 : "In 1",
                2 : "In 2",
                3 : "In 3"
            }
        },
        ZOUT : {
            name : "Zoom",
            type : "select",
            writeonly : true,
            values : {
                1 : "Out 1",
                2 : "Out 2",
                3 : "Out 3"
            }
        },
        FOIN : {
            name : "Focus",
            type : "select",
            writeonly : true,
            values : {
                1 : "In 1",
                2 : "In 2",
                3 : "In 3"
            }
        },
        FOUT : {
            name : "Focus",
            type : "select",
            writeonly : true,
            values : {
                1 : "Out 1",
                2 : "Out 2",
                3 : "Out 3"
            }
        },
        LSUP : {
            name : "Lens Shift",
            type : "select",
            writeonly : true,
            values : {
                1 : "Up 1",
                2 : "Up 2",
                3 : "Up 3"
            }
        },
        LSDW : {
            name : "Lens Shift",
            type : "select",
            writeonly : true,
            values : {
                1 : "Down 1",
                2 : "Down 2",
                3 : "Down 3"
            }
        },
        LSLF : {
            name : "Lens Shift",
            type : "select",
            writeonly : true,
            values : {
                1 : "Left 1",
                2 : "Left 2",
                3 : "Left 3"
            }
        },
        LSRH : {
            name : "Lens Shift",
            type : "select",
            writeonly : true,
            values : {
                1 : "Right 1",
                2 : "Right 2",
                3 : "Right 3"
            }
        },
        IROP : {
            name : "Iris",
            type : "select",
            writeonly : true,
            values : {
                1 : "Open 1",
                2 : "Open 2",
                3 : "Open 3"
            }
        },
        IRCL : {
            name : "Iris",
            type : "select",
            writeonly : true,
            values : {
                1 : "Close 1",
                2 : "Close 2",
                3 : "Close 3"
            }
        },
        BRIG : {
            name : "Brightness",
            type : "range",
            min : 0,
            max : 100
        },
        CNTR : {
            name : "Contrast",
            type : "range",
            min : 0,
            max : 100
        },
        SHRP : {
            name : "Sharpness",
            type : "range",
            min : 0,
            max : 20
        },
        LTR1 : {
            name : "Runtime",
            type : "text",
            readonly : true
        },
        LTR2 : {
            name : "Runtime",
            type : "text",
            readonly : true
        },
        LST1 : { 
            name : "Status",
            type : "select",
            readonly : true,
            values : {
                0 : "Broken",
                1 : "Warming up",
                2 : "On",
                3 : "Off",
                4 : "Cooling down",
                5 : "Missing"
            }
        },
        LMOD : {
            name : "Lamp Mode",
            type : "select",
            values : {
                0 : "Lamp 1",
                1 : "Lamp 2",
                2 : "Dual Lamps",
                3 : "Auto-switch"
            }
        },
        GABS : {
            name : "Gamma",
            type : "select",
            values : {
                0 : "Film 1",
                1 : "Film 2",
                2 : "Video 1",
                3 : "Video 2",
                7 : "Computer 1",
                8 : "Computer 2"
            }
        }
    },
    "audio-onkyo" : {
        MVL : {
            name : "Master Volume",
            type : "range",
            min : 0,
            max : 100 
        }
    },
    "switcher-dxp" : {
    },
    delay : {
        delay : {
            name : "us delay",
            type : "range",
            writeonly : true,
            min : 0,
            max : 10000000
        }
    }
    
};
    

