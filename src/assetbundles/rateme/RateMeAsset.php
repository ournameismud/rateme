<?php
/**
 * Rate Me plugin for Craft CMS 3.x
 *
 * Simple Rating plugin
 *
 * @link      http://ournameismud.co.uk/
 * @copyright Copyright (c) 2019 @cole007
 */

namespace ournameismud\rateme\assetbundles\RateMe;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    @cole007
 * @package   RateMe
 * @since     1
 */
class RateMeAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@ournameismud/rateme/assetbundles/rateme/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/RateMe.js',
        ];

        $this->css = [
            'css/RateMe.css',
        ];

        parent::init();
    }
}
