<?php

namespace Juanber84\Bundle\ScheduleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jobs
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Jobs
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \userid
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="userid", referencedColumnName="id", nullable=false)
     * })
     */      
    private $userid;

    /**
     * @var \proyectid
     *
     * @ORM\ManyToOne(targetEntity="Proyects")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="proyectid", referencedColumnName="id", nullable=false)
     * })
     */      
    private $proyectid;

    /**
     * @var \activityid
     *
     * @ORM\ManyToOne(targetEntity="Activity")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="activityid", referencedColumnName="id", nullable=false)
     * })
     */      
    private $activityid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="initdatetime", type="datetime")
     */
    private $initdatetime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="enddatetime", type="datetime")
     */
    private $enddatetime;

    /**
     * @var string
     *
     * @ORM\Column(name="observations", type="string", length=255)
     */
    private $observations;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userid
     *
     * @param \Noskasamos\AdminBundle\Entity\User $userid
     * @return userid
     */        
    public function setUserid(\Noskasamos\AdminBundle\Entity\User $userid = null)
    {
        $this->userid = $userid;
    }

    /**
     * Get userid
     *
     * @return \Noskasamos\ModelBundle\Entity\User
     */       
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Set proyectid
     *
     * @param \Noskasamos\AdminBundle\Entity\Proyects $proyectid
     * @return proyectid
     */        
    public function setProyectid(\Noskasamos\AdminBundle\Entity\Proyects $proyectid = null)
    {
        $this->proyectid = $proyectid;
    }

    /**
     * Get proyectid
     *
     * @return \Noskasamos\ModelBundle\Entity\Proyects
     */       
    public function getProyectid()
    {
        return $this->proyectid;
    }

    /**
     * Set activityid
     *
     * @param \Noskasamos\AdminBundle\Entity\Activity $activityid
     * @return activityid
     */        
    public function setActivityid(\Noskasamos\AdminBundle\Entity\Activity $activityid = null)
    {
        $this->activityid = $activityid;
    }

    /**
     * Get activityid
     *
     * @return \Noskasamos\ModelBundle\Entity\Activity
     */       
    public function getActivityid()
    {
        return $this->activityid;
    }

    /**
     * Set initdatetime
     *
     * @param \DateTime $initdatetime
     * @return Jobs
     */
    public function setInitdatetime($initdatetime)
    {
        $this->initdatetime = $initdatetime;
    
        return $this;
    }

    /**
     * Get initdatetime
     *
     * @return \DateTime 
     */
    public function getInitdatetime()
    {
        return $this->initdatetime;
    }

    /**
     * Set enddatetime
     *
     * @param \DateTime $enddatetime
     * @return Jobs
     */
    public function setEnddatetime($enddatetime)
    {
        $this->enddatetime = $enddatetime;
    
        return $this;
    }

    /**
     * Get enddatetime
     *
     * @return \DateTime 
     */
    public function getEnddatetime()
    {
        return $this->enddatetime;
    }

    /**
     * Set observations
     *
     * @param string $observations
     * @return Jobs
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;
    
        return $this;
    }

    /**
     * Get observations
     *
     * @return string 
     */
    public function getObservations()
    {
        return $this->observations;
    }
}
