<?php

namespace App\Services;

/**
 * Minimal, dependency-free HTML sanitizer.
 *
 * Strips all scripts/styles, removes event-handler attributes and
 * javascript:/data: URIs, and restricts markup to a safe allow-list.
 * Used before any CMS-authored HTML is rendered publicly.
 */
class HtmlSanitizer
{
    protected const ALLOWED_TAGS = [
        'p', 'br', 'hr', 'span', 'div', 'section', 'article',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'strong', 'b', 'em', 'i', 'u', 's', 'mark', 'small', 'sub', 'sup',
        'ul', 'ol', 'li', 'blockquote', 'pre', 'code',
        'a', 'img', 'figure', 'figcaption',
        'table', 'thead', 'tbody', 'tr', 'th', 'td',
        'iframe',
    ];

    protected const ALLOWED_ATTRS = [
        'id', 'class', 'style', 'title', 'href', 'target', 'rel',
        'src', 'alt', 'width', 'height', 'loading', 'frameborder',
        'allowfullscreen', 'referrerpolicy', 'colspan', 'rowspan',
    ];

    public static function clean(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        $html = (string) preg_replace('/<!--.*?-->/s', '', $html);

        $dom = new \DOMDocument();
        $prev = libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<html><head><meta charset="utf-8"></head><body>' . $html . '</body></html>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        $body = $dom->getElementsByTagName('body')->item(0);
        if (!$body) {
            return '';
        }

        self::sanitizeNode($dom, $body);

        $clean = '';
        foreach ($body->childNodes as $node) {
            $clean .= $dom->saveHTML($node);
        }

        return trim($clean);
    }

    protected static function sanitizeNode(\DOMDocument $dom, \DOMNode $node): void
    {
        if ($node->nodeType === XML_ELEMENT_NODE) {
            $tag = strtolower($node->nodeName);

            if (!in_array($tag, self::ALLOWED_TAGS, true)) {
                self::replaceWithChildren($dom, $node);
                return;
            }

            if (in_array($tag, ['iframe'])) {
                $src = $node->getAttribute('src') ?? '';
                if (!self::isSafeUrl($src, true)) {
                    self::replaceWithChildren($dom, $node);
                    return;
                }
            }

            self::cleanAttributes($node);

            if ($tag === 'a') {
                $node->setAttribute('rel', 'noopener noreferrer nofollow');
            }
        }

        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }
        foreach ($children as $child) {
            self::sanitizeNode($dom, $child);
        }
    }

    protected static function cleanAttributes(\DOMNode $node): void
    {
        $attrs = [];
        if ($node->attributes) {
            foreach ($node->attributes as $attr) {
                $attrs[$attr->nodeName] = $attr->nodeValue;
            }
        }

        foreach ($attrs as $name => $value) {
            $name = strtolower($name);
            if (!in_array($name, self::ALLOWED_ATTRS, true)) {
                $node->removeAttribute($name);
                continue;
            }
            if (preg_match('/^(on|xmlns)/i', $name)) {
                $node->removeAttribute($name);
                continue;
            }
            if (in_array($name, ['href', 'src']) && !self::isSafeUrl($value)) {
                $node->removeAttribute($name);
                continue;
            }
            if ($name === 'style' && self::hasDangerousCss($value)) {
                $node->removeAttribute($name);
                continue;
            }
        }
    }

    protected static function isSafeUrl(string $url, bool $allowEmbed = false): bool
    {
        $url = trim($url);
        if ($url === '' || $url === '#') {
            return true;
        }
        if (preg_match('/^\s*(javascript|data|vbscript):/i', $url)) {
            return false;
        }
        if ($allowEmbed && preg_match('/^https:\/\/(www\.)?(youtube\.com|youtu\.be|player\.vimeo\.com)/i', $url)) {
            return true;
        }
        return (bool) preg_match('/^https?:\/\//i', $url) || str_starts_with($url, '/') || str_starts_with($url, '#');
    }

    protected static function hasDangerousCss(string $css): bool
    {
        return (bool) preg_match('/(expression|javascript:|@import|url\s*\(\s*["\']?\s*(javascript|data):)/i', $css);
    }

    protected static function replaceWithChildren(\DOMDocument $dom, \DOMNode $node): void
    {
        $parent = $node->parentNode;
        if (!$parent) {
            return;
        }
        while ($node->firstChild) {
            $parent->insertBefore($node->firstChild, $node);
        }
        $parent->removeChild($node);
    }
}
