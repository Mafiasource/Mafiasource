<?PHP

$arrSliders = array(1 => "Slider1", "Slider2", "Slider3", "Slider4");
foreach($arrSliders AS $key => $slider) $langs[strtoupper($slider)] = $cms->getCMSById($key, $lang);
