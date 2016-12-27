<?php

namespace PMTest1\Providers;

use Plenty\Plugin\Templates\Twig;

/**
 * Class PMTest1ServiceDataProvider
 * @package PMTest1\Providers
 */
class PMTest1ServiceDataProvider
{
    /**
     * @param Twig $twig
     * @param $args
     * @return string
     */
    public function call(   Twig $twig,
        $args)
    {
        return $twig->render('PMTest1::content.head');
    }
}