<?php

namespace delasign\craftcmsstarterplugin\assetbundles\cpsectionassetbundle;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Oscar de la Hera Gomez
 * @package   Mail Service
 * @since     1.0.0
 */
class CpSectionAssetBundle extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@delasign/craftcmsstarterplugin/assetbundles/cpsectionassetbundle/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/index.js',
        ];

        $this->css = [
            'css/index.css',
        ];

        parent::init();
    }
}