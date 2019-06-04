<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class LayuiAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'layuiadmin/layui/css/layui.css',
		'layuiadmin/style/admin.css',
        'static/css/iconfont.css',
        'static/awesome/css/font-awesome.min.css',
        'static/css/style.css',
    ];
    public $js = [
		'layuiadmin/layui/layui.js',
        'static/js/common.js',
    ];
    public $depends = [
    ];

    //定义按需加载css方法，注意加载顺序在最后
    public static function addCss($view, $cssfile) {  
        $view->registerCssFile($cssfile, [AppAsset::className(), 'depends' => 'backend\assets\LayuiAsset']);  
    }

    //定义按需加载JS方法，注意加载顺序在最后
    public static function addScript($view, $jsfile) {
        $view->registerJsFile($jsfile, [AppAsset::className(), 'depends' => 'backend\assets\LayuiAsset']);
    }
}
