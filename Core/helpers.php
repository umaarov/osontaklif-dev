<?php

$__purifier = null;
function purify(?string $dirtyHtml): string
{
    global $__purifier;

    if ($dirtyHtml === null || $dirtyHtml === '') {
        return '';
    }

    if ($__purifier === null) {
        $config = HTMLPurifier_Config::createDefault();

        $config->set('URI.Enable', true);

        $config->set('HTML.SafeIframe', true);
        $config->set('URI.SafeIframeRegexp', '%^(https://www.youtube.com/embed/|https://player.vimeo.com/video/)%');

        $config->set('HTML.Allowed', 'p,b,strong,i,em,u,a[href|title],ul,ol,li,br,img[src|alt],iframe[src|width|height|frameborder|allowfullscreen]');

        $__purifier = new HTMLPurifier($config);
    }

    return $__purifier->purify($dirtyHtml);
}