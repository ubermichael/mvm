<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\ManuscriptContribution;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;

/**
 * Generated by Webonaute\DoctrineFixtureGenerator.
 */
class ManuscriptContributionFixtures extends Fixture implements DependentFixtureInterface {
    /**
     * Set loading order.
     *
     * @return int
     */
    public function getDependencies() {
        return [
            PersonFixtures::class,
            ManuscriptFixtures::class,
            ManuscriptRoleFixtures::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager) : void {
        $manager->getClassMetadata(ManuscriptContribution::class)->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

        $item1 = new ManuscriptContribution();
        $item1->setNote("<p>\"Property of M Simpson\" handwritten on the front page with date \"Summer '75\"</p>");
        $item1->setPerson($this->getReference('_reference_Person1'));
        $item1->setRole($this->getReference('_reference_ManuscriptRole1'));
        $item1->setManuscript($this->getReference('_reference_Manuscript1'));
        $item1->setCreated(new DateTimeImmutable('2019-07-29 22:50:42'));
        $item1->setUpdated(new DateTimeImmutable('2019-07-29 22:50:42'));
        $this->addReference('_reference_ManuscriptContribution1', $item1);
        $manager->persist($item1);

        $manager->flush();
    }
}
