<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * Theme
 *
 * @ORM\Table(name="theme")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ThemeRepository")
 */
class Theme extends AbstractTerm
{

    /**
     * @var Collection|Manuscript[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Manuscript", mappedBy="themes")
     */
    private $manuscripts;

    public function __construct() {
        parent::__construct();
        $this->manuscripts = new ArrayCollection();
    }

    /**
     * Add manuscript.
     *
     * @param \AppBundle\Entity\Manuscript $manuscript
     *
     * @return Theme
     */
    public function addManuscript(\AppBundle\Entity\Manuscript $manuscript)
    {
        $this->manuscripts[] = $manuscript;

        return $this;
    }

    /**
     * Remove manuscript.
     *
     * @param \AppBundle\Entity\Manuscript $manuscript
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeManuscript(\AppBundle\Entity\Manuscript $manuscript)
    {
        return $this->manuscripts->removeElement($manuscript);
    }

    /**
     * Get manuscripts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManuscripts()
    {
        return $this->manuscripts;
    }
}
