<?php

// Original text-based brand artwork. Run with a local TrueType font path.
$font = $argv[1] ?? 'C:/Windows/Fonts/arial.ttf';
if (! is_file($font)) {
    exit("Pass a TrueType font path.\n");
}
$image = imagecreatetruecolor(1200, 630);
$navy = imagecolorallocate($image, 24, 49, 83);
$white = imagecolorallocate($image, 255, 255, 255);
$muted = imagecolorallocate($image, 177, 198, 227);
imagefill($image, 0, 0, $navy);
imagettftext($image, 68, 0, 75, 170, $white, $font, 'codewithronn');
imagettftext($image, 24, 0, 80, 238, $muted, $font, 'Roni Zeki / Fullstack Developer');
imagettftext($image, 39, 0, 80, 382, $white, $font, 'Thoughtful code. Real business impact.');
imagettftext($image, 22, 0, 80, 465, $muted, $font, '6+ years building reliable web solutions.');
imagettftext($image, 15, 0, 80, 554, $muted, $font, 'INDONESIA / OPEN TO WORKING WORLDWIDE');
imagewebp($image, dirname(__DIR__).'/public/images/og.webp', 88);
echo "Updated social preview for codewithronn.\n";
