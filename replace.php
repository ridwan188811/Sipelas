<?php
$dir = new RecursiveDirectoryIterator('d:\laragon\spls\resources\views');
$ite = new RecursiveIteratorIterator($dir);
foreach($ite as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) == 'php') {
        $path = $file->getRealPath();
        $c = file_get_contents($path);
        
        $c = str_replace('Auth::user()', '(Auth::guard(\'admin\')->check() ? Auth::guard(\'admin\')->user() : Auth::guard(\'warga\')->user())', $c);
        $c = str_replace('->user->', '->warga->', $c);
        $c = str_replace('->user?', '->warga?', $c);
        $c = str_replace('user_id', 'warga_id', $c);
        
        file_put_contents($path, $c);
    }
}
echo "Done replacing.";
