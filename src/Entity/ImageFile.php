<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImageFile.
 *
 * @ORM\Table(name="image_file")
 * @ORM\Entity(repositoryClass="App\Repository\ImageFileRepository")
 */
class ImageFile extends Image {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Force all entities to provide a stringify function.
     *
     * @return string
     */
    public function __toString : string {
        return get_class() . ' #' . $this->id;
    }

    public function getType() {
        return Image::FILE;
    }
}
