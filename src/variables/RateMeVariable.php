<?php
/**
 * Rate Me plugin for Craft CMS 3.x
 *
 * Simple Rating plugin
 *
 * @link      http://ournameismud.co.uk/
 * @copyright Copyright (c) 2019 @cole007
 */

namespace ournameismud\rateme\variables;

use ournameismud\rateme\RateMe;

use Craft;

/**
 * @author    @cole007
 * @package   RateMe
 * @since     1
 */
class RateMeVariable
{
    // Public Methods
    // =========================================================================

    /**
     * @param null $optional
     * @return string
     */
    public function getRatings()
    {
        $record = RateMe::getInstance()->rateMeService->getRatings();
        return $record;
    }
    
    public function getRating( $elementId )
    {
        
        $record = RateMe::getInstance()->rateMeService->getRating( $elementId );
        return $record;
    }

    public function getAverage( $elementId )
    {
        
        $count = RateMe::getInstance()->rateMeService->getAverage( $elementId );
        return $count;
    }
}
