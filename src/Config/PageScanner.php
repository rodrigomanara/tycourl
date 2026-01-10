<?php

declare(strict_types=1);

namespace Codediesel\Config;
class PageScanner
{
    function getClassWithNamespace($filePath)
    {
        $phpCode = file_get_contents($filePath);
        $tokens = token_get_all($phpCode);

        $namespace = '';
        $class = '';

        for ($i = 0; $i < count($tokens); $i++) {
            // Find Namespace
            if ($tokens[$i][0] === T_NAMESPACE) {
                for ($j = $i + 1; $j < count($tokens); $j++) {
                    if ($tokens[$j] === ';') {
                        $i = $j; // Skip to end of namespace declaration
                        break;
                    }
                    if (is_array($tokens[$j])) {
                        $namespace .= $tokens[$j][1];
                    }
                }
            }

            // Find Class
            if ($tokens[$i][0] === T_CLASS) {
                // Check if it's a "class name" and not "::class"
                if ($tokens[$i - 1][0] !== T_DOUBLE_COLON) {
                    for ($j = $i + 1; $j < count($tokens); $j++) {
                        if ($tokens[$j] === '{') {
                            break;
                        }
                        if (is_array($tokens[$j]) && $tokens[$j][0] === T_STRING) {
                            $class = $tokens[$j][1];
                            break 2; // Found class, exit loops
                        }
                    }
                }
            }
        }

        return $namespace ? trim($namespace) . '\\' . $class : $class;
    }
}

$pageScanner = new PageScanner();
$path = __DIR__;
$pages = glob($path . '/../Pages/*/*.php');

$pagesList = [];
if (is_dir($path . '/../Pages/')) {
    foreach ($pages as $page) {
        $namespace = $pageScanner->getClassWithNamespace($page);
        $pagesList[] = $namespace;
    }
}
return $pagesList;