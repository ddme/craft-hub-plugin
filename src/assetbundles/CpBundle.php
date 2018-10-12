<?php

namespace ddme\crafthub\assetbundles;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class CpBundle extends AssetBundle
{
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = '@ddme/crafthub/assetbundles';

        // define the dependencies
        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'dist/bundle.js',
        ];

        $this->css = [
            'dist/styles.css',
        ];

        parent::init();
    }
}
