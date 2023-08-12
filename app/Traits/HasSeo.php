<?php

namespace App\Traits;

use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;

trait HasSeo
{
    /**
     * @param  string|null  $title
     * @param  string|null  $description
     * @param  array  $keywords
     * @param  string|null  $url
     * @param  string|null  $twitterUsername
     * @param  string|null  $image
     * @return void
     */
    public function seo(
        ?string $title = null,
        ?string $description = null,
        ?string $keywords = null,
        ?string $url = null,
        ?string $image = null,
        ?string $twitterUsername = null,
    ): void {
        SEOTools::setTitle($title ?? config('app.name'));
        SEOTools::setDescription(strip_tags($description) ?? "");
        SEOMeta::setKeywords(!empty($keywords) ? $keywords : "");
        SEOTools::opengraph()->setType('website');
        SEOTools::twitter()->setType('summary');
        if (!empty($url)) {
            SEOTools::opengraph()->setUrl($url);
            SEOTools::setCanonical($url);
        } else {
            SEOTools::opengraph()->setUrl(url()->current());
            SEOTools::setCanonical(url()->current());
        }
        if (!empty($image)) {
            SEOTools::addImages($image);
        } else {
            SEOTools::addImages(asset('templates/landing-page/assets/images/logo-dark.png'));
        }
        if (!empty($twitterUsername)) {
            SEOTools::twitter()->setSite($twitterUsername);
        }
    }
}
