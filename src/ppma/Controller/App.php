<?php


namespace ppma\Controller;

use ppma\Controller;


class App extends Controller
{

    /**
     * @return string
     */
    public function home()
    {
        return $this->render('app');
    }

}