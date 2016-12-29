<?php

namespace PMTest\Providers;

use Plenty\Plugin\Templates\Twig;

/**
 * Class PMTestServiceDataProvider
 * @package PMTest\Providers
 */
class PMTestServiceDataProvider
{
    /**
     * @param Twig $twig
     * @param $args
     * @return string
     */
    public function call(   Twig $twig,
        $args)
    {
        return $twig->render('PMTest::content.head');
    }
}