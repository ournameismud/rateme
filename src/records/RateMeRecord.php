<?php
/**
 * Rate Me plugin for Craft CMS 3.x
 *
 * Simple Rating plugin
 *
 * @link      http://ournameismud.co.uk/
 * @copyright Copyright (c) 2019 @cole007
 */

namespace ournameismud\rateme\records;

use ournameismud\rateme\RateMe;

use Craft;
use craft\db\ActiveRecord;

/**
 * @author    @cole007
 * @package   RateMe
 * @since     1
 */
class RateMeRecord extends ActiveRecord
{
    // Public Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rateme_ratemerecord}}';
    }
}
