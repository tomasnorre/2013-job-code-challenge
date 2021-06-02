<?php

class View {

    private $musicRelease;

    /**
     * Default constructor
     * 
     * @param MusicRelease $musicRelease
     */
    public function __construct(MusicRelease $musicRelease){
        $this->musicRelease = $musicRelease;
    }

    /**
     * xmlOutputToFile, prepare an array entry for later processing. 
     * 
     * @return string
     */
    public function xmlOutputToFile() {
        $array = array(
                'id' => $this->musicRelease->id,
                'trackCount' => $this->musicRelease->trackCount,
                'title' => $this->musicRelease->title
        );
        
        return $array;
    }

    /**
     * createXMLFile, creates output.xml in root-folder of this code-project.
     * 
     * @param obj $matchingReleases
     */
    public function createXMLFile($matchingReleases) {
        
        // Create a new XML object, with root-node matchingReleases
        $xml = new SimpleXMLElement('<matchingReleases/>');
        
        // Loops through $matchingrelease and create a child-node for each release
        foreach ($matchingReleases as $matchingRelease ){
            
            $release = $xml->addChild('release');
            $release->addAttribute('id',$matchingRelease['id']);
            $release->name = $matchingRelease['title'];
            $release->trackCount = $matchingRelease['trackCount'];
            
        }       

        // Building the domtree, and buitify the XML.
        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;
        
        // Stores XML to output.xml
        $dom->save(DIRNAME(__FILE__).'/../output.xml');
    }

}

