<?php

namespace Setcooki\Wp\Role\Visibility;

use Setcooki\Wp\Role\Visibility\Traits\Singleton;

/**
 * Class Plugin
 * @package Setcooki\Wp\Role\Visibility
 */
class Plugin
{
    use Singleton;


    /**
     * @var null
     */
    public static $options = [];



    /**
     * @throws \Exception
     */
    public function init($options = null)
    {
        $this->setup();

        if(is_array($options))
        {
           static::$options = array_merge(static::$options, $options);
        }

        Post::getInstance($this);
        MetaBox::getInstance($this);
    }


    /**
     * @throws \Exception
     */
    protected function setup()
    {
    }


    /**
     * @throws \Exception
     */
    public function activate()
    {
    }


    /**
     *
     */
    public function deactivate()
    {
    }


    /**
     *
     */
    public static function uninstall()
    {
    }
}