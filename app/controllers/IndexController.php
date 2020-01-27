<?php
declare(strict_types=1);

namespace Timetracker\Controllers;

class IndexController extends ControllerBase
{
    public function indexAction(): void
    {
        $this->assets->addCss('css/style.css', true);
    }

    public function route404Action(): void {

    }
}
