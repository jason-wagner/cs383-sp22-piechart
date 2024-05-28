<?php

$width = 400;
$height = 300;

$font = "/home/mathcs/courses/cs383/Vera.ttf";

$title = "Favorite Animal";

$xChart = 125;
$yChart = 175;

$legendBoxSize = 20;
$xLegend = 25 + 200 + 25;

$offsetAngle = 270;

$widthChart = 200;
$heightChart = 200;

$items = [
    'Dog' => 15,
    'Cat' => 5,
    'Horse' => 10,
	'Rabbit' => 5,
	'Pig' => 10
];

$total = array_sum($items);

$data = [];

foreach($items as $name => $n) {
	$data[] = [
		'label' => $name,
		'value' => $n,
		'start_angle' => $offsetAngle + array_sum(array_column($data, 'value'))/$total * 360,
		'end_angle' => $offsetAngle + (array_sum(array_column($data, 'value')) + $n)/$total * 360
	];
}

$im = imagecreate($width, $height);

$white = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
$black = imagecolorallocate($im, 0x00, 0x00, 0x00);
$red = imagecolorallocate($im, 0xFF, 0x00, 0x00);
$green = imagecolorallocate($im, 0x00, 0xFF,0x00);
$blue = imagecolorallocate($im, 0x00, 0x00, 0xFF);
$purple = imagecolorallocate($im, 0xFF, 0x00, 0xFF);
$yellow = imagecolorallocate($im, 0xFF, 0xFF, 0x00);

$chart_colors = [$red, $green, $blue, $purple, $yellow];

$c = imagettfbbox(30, 0, $font, $title);
$widthTitle = abs($c[2] - $c[0]);
$xTitle = ($width - $widthTitle)/2;

imagettftext($im, 30, 0, $xTitle, 40, $black, $font, $title);

foreach($data as $p => $i) {
	$color = $chart_colors[$p % count($chart_colors)];

	imagefilledarc($im, $xChart, $yChart, $widthChart, $heightChart, $i['start_angle'], $i['end_angle'], $color, IMG_ARC_PIE);

	$y2 = $yChart + $legendBoxSize + ($legendBoxSize + 10) * ($p - count($data)/2) + 5;

	imagefilledrectangle($im, $xLegend, $y2 - $legendBoxSize, $xLegend + $legendBoxSize, $y2, $color);
	imagettftext($im, 18, 0, $xLegend + $legendBoxSize + 5, $y2, $black, $font, $i['label']);
}

header("Content-Type: image/png");
imagepng($im);
imagedestroy($im);
