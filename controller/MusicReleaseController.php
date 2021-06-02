<?php

require_once 'model/MusicRelease.php';

class MusicReleaseController {

    private $musicRelease;

    /**
     * Default contructor for MusicReleaseController
     */
    public function __construct() {
        $this->musicRelease = new MusicRelease();
    }

    /**
     * Init, uses the storeReleases to get releases from XML, 
     * afterwards its processed by the filter-function releasesMatchReleaseDateAndTracks()
     * 
     * @return array of objects
     */
    public function init() {
        $releases = $this->musicRelease->storeReleases();
        $releasesMatch = $this->musicRelease->releasesMatchReleaseDateAndTracks($releases);

        return $releasesMatch;
    }

}

