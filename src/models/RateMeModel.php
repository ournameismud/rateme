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
class RateMeModel extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $someAttribute = 'Some Default';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['element', 'number','min' => 1],
            ['rate', 'number','min' => 1],
            ['owner', 'string'],
        ];
    }
}
