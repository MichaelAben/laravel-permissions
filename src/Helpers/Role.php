<?php


namespace MabenDev\Permissions\Helpers;


/**
 * Class Role
 * @package MabenDev\Permissions\Helpers
 *
 * @author Michael Aben
 */
class Role
{
    /**
     * @var Role
     */
    protected static $instance;

    /**
     * @var mixed
     */
    protected $user;

    /**
     * Role constructor.
     */
    public function __construct()
    {
        $this->user = config('MabenDevPermissions.user')();
    }

    /**
     * @return Role
     */
    protected static function getInstance()
    {
        if(empty(self::$instance)) self::$instance = new Role();
        return self::$instance;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return self::getInstance()->$name(...$arguments);
    }

    /**
     * @param $role
     *
     * @return boolean
     */
    protected function has($role)
    {
        return $this->user->hasRole($role);
    }

    /**
     * @param $roles
     *
     * @return boolean
     */
    protected function hasAny($roles)
    {
        return $this->user->hasAnyRole($roles);
    }

    /**
     * @param $roles
     *
     * @return boolean
     */
    protected function hasAll($roles)
    {
        return $this->user->hasAllRoles($roles);
    }
}
