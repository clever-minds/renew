<?php

declare(strict_types=1);

namespace App\Services\SEO;

use Illuminate\Support\Facades\Request;

class SeoService
{
    /**
     * Generates standard meta tags, OpenGraph, and Twitter cards.
     */
    public function generateTags(
        string $title,
        string $description,
        ?string $image = null,
        string $type = 'website'
    ): string {
        $url = Request::url();
        $appName = config('app.name');
        $siteTitle = $title ? "{$title} | {$appName}" : $appName;
        $defaultImage = asset('images/default-og.png');
        $ogImage = $image ?? $defaultImage;

        return <<<HTML
            <title>{$siteTitle}</title>
            <meta name="description" content="{$description}">
            
            <!-- Open Graph -->
            <meta property="og:title" content="{$siteTitle}">
            <meta property="og:description" content="{$description}">
            <meta property="og:type" content="{$type}">
            <meta property="og:url" content="{$url}">
            <meta property="og:image" content="{$ogImage}">
            
            <!-- Twitter -->
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:title" content="{$siteTitle}">
            <meta name="twitter:description" content="{$description}">
            <meta name="twitter:image" content="{$ogImage}">
            
            <link rel="canonical" href="{$url}">
        HTML;
    }
}
