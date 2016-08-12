<?php

namespace Nikapps\OrtcPhp\Models;

class Channel
{
    const PERMISSION_WRITE = 'w';
    const PERMISSION_READ = 'r';

    /**
     * Construct.
     *
     * @param string $name
     * @param string $permission
     */
    public function __construct($name = null, $permission = self::PERMISSION_READ)
    {
        $this->setName($name);
        $this->setPermission($permission);
    }

    /**
     * name of channel.
     *
     * @var string
     */
    private $name;

    /**
     * channel permission.
     *
     * @var string
     */
    private $permission;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @param string $permission
     *
     * @return $this
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * To string returned name.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
