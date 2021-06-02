<?php

class MusicRelease {

    /**
     * id of the MusicRelease object
     * 
     * @var string 
     */
    public $id;
    
    /**
     * title for MusicRelease object
     * 
     * @var string 
     */
    public $title;
    
    /**
     * genre for MusicRelease object
     * 
     * @var string 
     */
    public $genre;
    
    /**
     * releaseDate for MusicRelease object
     * 
     * @var string 
     */
    public $releaseDate;
    
    /**
     * formats for MusicRelease object, 
     * which formats is the relesae released in, CD, Cassette etc.
     * 
     * @var string 
     */
    public $formats;
    
    /**
     * studiolive for MusicRelease object, 
     * is the music recorded in the studio or live.
     * 
     * @var string 
     */
    public $studiolive;
    
    /**
     * label for MusicRelease object, 
     * which label has produced the music release
     * 
     * @var string 
     */
    public $label;
    
    /**
     * catalogueNumber for MusicRelease object
     * 
     * @var string 
     */
    public $catalogueNumber;
    
    /**
     * trackCount for MusicRelease object, 
     * how many tracks does the release contain
     * 
     * @var int 
     */
    public $trackCount;
    
    /**
     * cover for MusicRelease object,
     * url for image cover
     * 
     * @var string 
     */
    public $cover;

    
    /**
     * default contructor
     * 
     * @return \MusicRelease
     */
    public function __construct() {
        return $this;
    }

    /**
     * generic setProperty function,
     * sets the property with a given value.
     * 
     * @param string $property
     * @param string $value, if value of trackCount is set, integer is input as value.
     */
    public function setProperty($property, $value) {
        $this->$property = $value;
    }

    /**
     * generic getProperty function, 
     * gets the value of a given property
     * 
     * @param string $property
     * @return depends on the value, string or integer
     */
    public function getProperty($property) {
        return $this->$property;
    }

    /**
     * storeReleases, looping through XML-content and store MusicRelease objects
     * in array, for later filtering.
     * 
     * @return \MusicRelease
     * @throws Exception
     */
    public function storeReleases() {

        // Parse configuration.ini
        $configuration = parse_ini_file('configuration.ini');

        // Release array - Will be returned from this function
        $releases = array();

        // Check if local or external file should be used.
        if ($configuration['loadExternalSource']) {
            $xmlFile = 'http://musicmoz.org/xml/musicmoz.bandsandartists.d.delirious.xml';
        } else {
            $xmlFile = dirname(__FILE__) . '/../music.xml';
        }

        // Check if XML-source is available.
        try {
            if (!$xmlContent = simplexml_load_file($xmlFile)) {
                throw new Exception('Cannot read xml-source');
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }

        // For each category, we check if $categoryValue has an item-child and if item type equals release
        // we loops through the release and store a MusicRelease object in array.
        foreach ($xmlContent->category as $categoryValue) {
            if ($categoryValue->item) {
                if ((string) $categoryValue->item->attributes()->type === 'release') {

                    foreach ($categoryValue->item as $itemValue) {

                        // $id set to get a correct offset for array key.
                        $id = (string) $itemValue->attributes()->id;

                        // Initiate object and set values
                        $musicRelease = new MusicRelease();
                        $musicRelease->setProperty('id', $id);
                        $musicRelease->setProperty('title', (string) $itemValue->title);
                        $musicRelease->setProperty('genre', (string) $itemValue->genre);
                        $musicRelease->setProperty('releaseDate', (string) $itemValue->releasedate);
                        $musicRelease->setProperty('formats', (string) $itemValue->formats);
                        $musicRelease->setProperty('studiolive', (string) $itemValue->studiolive);
                        $musicRelease->setProperty('label', (string) $itemValue->label);
                        $musicRelease->setProperty('catalogueNumber', (string) $itemValue->cataloguenumber);
                        
                        // Counting tracks
                        $trackCount = 0;
                        
                        // Check if release has more than one disc, trackCount differ depending on amount of discs
                        if ($itemValue->tracklisting->disc) {
                            foreach ($itemValue->tracklisting->disc as $discs) {
                                foreach ($discs->track as $track) {
                                    $trackCount++;
                                }
                            }
                        } else {
                            foreach ($itemValue->tracklisting as $tracks) {
                                foreach ($tracks as $track) {
                                    $trackCount++;
                                }
                            }
                        }
                        $musicRelease->setProperty('trackCount', $trackCount);
                        $musicRelease->setProperty('cover', (string) $itemValue->cover->attributes()->src);

                        // store Object in array
                        $releases[$id] = $musicRelease;
                    }
                }
            } else {
                // if item type not equals release we jump to next item.
                continue;
            }
        }
        return $releases;
    }

    /**
     * releasesMatchReleaseDateAndTracks,
     * simple function that returns objects which match filteroption into a array
     * that can be processed later with the view.
     * 
     * @param type $musicReleases
     * @return type
     */
    public function releasesMatchReleaseDateAndTracks($musicReleases) {

        $releaseMatchArray = array();

        // Loops through musicReleases.
        foreach ($musicReleases as $musicRelease) {
            if ($musicRelease->releaseDate < '2001.01.01' && $musicRelease->trackCount > 10) {
                $releaseMatchArray[] = $musicRelease;
            }
        }

        return $releaseMatchArray;
    }

}

