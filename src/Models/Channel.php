<?php
namespace Nikapps\OrtcPhp\Models;

class Channel
{

    const PERMISSION_WRITE = 'w';
    const PERMISSION_READ = 'r';

    /**
     * name of channel
     *
     * @var string
     */
    private $name;

    /**
     * channel permission
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
     * @return $this
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;

        return $this;
    }
}
