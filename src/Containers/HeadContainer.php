<?php

namespace PMTest\Containers;

use Plenty\Plugin\Templates\Twig;

class HeadContainer
{
    public function call(Twig $twig):string
    {
        return $twig->render('PMTest::content.head');
    }
}