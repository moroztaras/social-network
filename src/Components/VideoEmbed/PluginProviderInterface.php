<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 7/13/2017
 * Time: 12:21 PM.
 */

namespace App\Components\VideoEmbed;

interface PluginProviderInterface
{
    public static function getIdFromInput($input);

    public function renderEmbedCode($width, $height, $autoplay);
}
