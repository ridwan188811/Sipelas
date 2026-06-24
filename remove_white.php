<?php
$src = imagecreatefromjpeg('d:/laragon/spls/public/images/stempel.jpg');
$w = imagesx($src);
$h = imagesy($src);
$dest = imagecreatetruecolor($w, $h);
imagesavealpha($dest, true);
$trans_colour = imagecolorallocatealpha($dest, 0, 0, 0, 127);
imagefill($dest, 0, 0, $trans_colour);

for($x=0; $x<$w; $x++) {
    for($y=0; $y<$h; $y++) {
        $rgb = imagecolorat($src, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        
        // Make bright pixels transparent
        if($r > 180 && $g > 180 && $b > 180) {
            imagesetpixel($dest, $x, $y, $trans_colour);
        } else {
            // Calculate a blend to make edges smoother
            // The darker it is, the more opaque
            $brightness = ($r + $g + $b) / 3;
            $alpha = 0; // fully opaque
            if($brightness > 100) {
                // map 100-180 to alpha 0-120
                $alpha = (int)(($brightness - 100) * (120/80));
                if($alpha > 127) $alpha = 127;
            }
            $color = imagecolorallocatealpha($dest, $r, $g, $b, $alpha);
            imagesetpixel($dest, $x, $y, $color);
        }
    }
}
imagepng($dest, 'd:/laragon/spls/public/images/stempel.png');
imagedestroy($src);
imagedestroy($dest);
echo "Done";
