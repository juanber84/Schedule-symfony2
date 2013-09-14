<?php

namespace Juanber84\Bundle\ScheduleBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        //$this->roles = array('ROLE_ADMIN');
        $this->roles = array('ROLE_USER');
    }

    public function __toString()
    {
        return $this->getUsername();
    }

}
