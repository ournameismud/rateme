<?php
/**
 * Rate Me plugin for Craft CMS 3.x
 *
 * Simple Rating plugin
 *
 * @link      http://ournameismud.co.uk/
 * @copyright Copyright (c) 2019 @cole007
 */

namespace ournameismud\rateme\services;

use ournameismud\rateme\RateMe;
use ournameismud\rateme\records\RateMeRecord AS RatingRecord;

use Craft;
use craft\base\Component;

/**
 * @author    @cole007
 * @package   RateMe
 * @since     1
 */
class RateMeService extends Component
{
    // Public Methods
    // =========================================================================

    protected $sessionName = 'ournameismud_rateme';
    /*
     * @return mixed
     */
    protected function setCookieName()
    {
        return md5(uniqid(mt_rand(), true));
    }

    public function getAverage( $elementId )
    {
        $ratings = RatingRecord::find()
            ->where([
                'element' => $elementId
            ])->all(); 
        $count = [];    
        foreach( $ratings AS $rating ) {
            $count[] = $rating->rate;
        }    
        $count = array_filter($count);
        return [
            'count' => count($count),
            'rating' => array_sum($count)/count($count)
        ];
    }

    public function getRating( $elementId )
    {
        $site = Craft::$app->getSites()->getCurrentSite();
        
        $user = Craft::$app->getUser();  
        $session = Craft::$app->getSession();
        if($user->id == null) {
            $sessionName = $session[$this->sessionName];
            if (!$sessionName) {
                $sessionName = $this->setCookieName();
                $session->set($this->sessionName, $sessionName);
            }
            $owner = $sessionName;
        } else {
            $owner = $user->id;
        }

        $ratings = RatingRecord::find()
            ->where([
                'element' => $elementId,
                'owner' => $owner
            ])->one();    

        return $ratings ? $ratings->rate : null; 
    }

    public function convertRatings( $userRef )
    {
        $user = Craft::$app->getUser();
        $site = Craft::$app->getSites()->getCurrentSite();
        $rels = RatingRecord::updateAll(['owner'=>$user->id],[
            'owner' => $userRef
        ]);        
    }
    public function addRating( $rating, $elementId )
    {
        $site = Craft::$app->getSites()->getCurrentSite();
        
        $user = Craft::$app->getUser();  
        $session = Craft::$app->getSession();
        if($user->id == null) {
            $sessionName = $session[$this->sessionName];
            if (!$sessionName) {
                $sessionName = $this->setCookieName();
                $session->set($this->sessionName, $sessionName);
            }
            $owner = $sessionName;
        } else {
            $owner = $user->id;
        }

        $ratings = RatingRecord::find()
            ->where([
                'element' => $element,
                'owner' => $owner
            ])->one();    
        if (!$ratings)  {
            $ratings = new RatingRecord;            
        } 

        $ratings->element = $elementId;
        $ratings->rate = $rating;
        $ratings->owner = $owner;
        $ratings->siteId  = $site->id;

        $record = $ratings->save();
        // Craft::dd($ratings);
        // $result = 'something';

        return $record;
    }
}
