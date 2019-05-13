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
use craft\helpers\FileHelper;
use craft\mail\Message;

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
        if (!$ratings) return null;
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

    protected function notifyRating($recipients, $elementId, $rating) 
    {
        $site = Craft::$app->getSites()->getCurrentSite();        
        // $recipients = explode(',',$recipients);
        $subject = "You have received a rating";
        $file = Craft::getAlias('@storage/logs/rateme.log');

    
        $body = "A rating has been posted on the " . $site->name . " website:\r\n\r\n";
        
        $request = Craft::$app->getRequest();
        $referrer = $request->getReferrer();
        
        $body .= "Page: " . $referrer . "\r\n";
        $body .= "Rating: " . $rating . "\r\n";
        
        $recipients = explode(',',$recipients);
        foreach ($recipients AS $recipient) {
            $mailer = Craft::$app->getMailer();
            $message = (new Message())
                ->setTo( $recipients )
                ->setSubject( $subject )
                ->setTextBody( $body );
            if ($mailer->send( $message )) {
                $log = date('Y-m-d H:i:s') . " Rating " . $rating . " for URL " . $referrer . " added. Email sent successfully to " . $recipient ."\n";                
            } else {
                $log = date('Y-m-d H:i:s') . " Rating " . $rating . " for URL " . $referrer . " added. Email not sent to " . $recipient ."\n";
            }    
            FileHelper::writeToFile($file, $log, ['append' => true]);
        }
        return true;
        
    }

    public function addRating( $rating, $elementId )
    {
        $site = Craft::$app->getSites()->getCurrentSite();        
        $user = Craft::$app->getUser();  
        $session = Craft::$app->getSession();
        $settings = RateMe::$plugin->getSettings();
        $recipients = $settings->recipients;

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
        if (!$ratings)  {
            $ratings = new RatingRecord;            
        } 

        $ratings->element = $elementId;
        $ratings->rate = $rating;
        $ratings->owner = $owner;
        $ratings->siteId  = $site->id;

        $record = $ratings->save();

        if( $recipients && $record) {
            $sent = $this->notifyRating($recipients, $elementId, $rating);
        }
        // Craft::dd($ratings);
        // $result = 'something';

        return $record;
    }
}
