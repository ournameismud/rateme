<?php
/**
 * Rate Me plugin for Craft CMS 3.x
 *
 * Simple Rating plugin
 *
 * @link      http://ournameismud.co.uk/
 * @copyright Copyright (c) 2019 @cole007
 */

namespace ournameismud\rateme\models;

use ournameismud\rateme\RateMe;

use Craft;
use craft\base\Model;

/**
 * @author    @cole007
 * @package   RateMe
 * @since     1
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $loggedIn = '1';
    public $recipients = '';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['loggedIn', 'boolean'],
            ['recipients', 'string']
        ];
    }
}
