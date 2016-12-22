<?php

namespace PMTest1\Containers;

use Plenty\Plugin\Templates\Twig;

class HeadContainer
{
    public function call(Twig $twig):string
    {
        return $twig->render('PMTest1::content.head');
    }
}