<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Artiste;
use App\Entity\Style;
use App\Entity\Pays;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArtistFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');


        $arr_styles = [1=>'Rock', 2=>'Techno', 3=>'Pop', 4=>'Post-rock', 5=>'Reggae'];
        foreach($arr_styles as $key => $one_style) {
                $style = new Style();
                $style->setId($key)
                    ->setStyle($one_style);
                $manager->persist($style);
                $this->addReference('style'.$style->getId(), $style);
        }

        $arr_pays = [1=>'France', 2=>'Islande', 3=>'Etats-Unis', 4=>'Espagne'];
        foreach($arr_pays as $key => $one_pays) {
            $pays = new Pays();
            $pays->setId($key)
                ->setCode(strtoupper(substr($one_pays, 0, 3)))
                ->setPays($one_pays);
            $manager->persist($pays);
            $this->addReference('pays'.$pays->getId(), $pays);
        }

        $genres = ['men', 'women'];
        $types = ['Groupe', 'Solo'];
        $artist = new Artiste();
        $artist->setNom($faker->lastName())
                ->setDescription($faker->paragraphs(2, true))
                ->setImage('https://randomuser.me/api/portraits/'.$faker->randomElement($genres).'/1.jpg')
                ->setType($faker->randomElement($types))
                ->setSiteWeb($faker->url())
                ->setStyle($this->getReference('style'.mt_rand(1,count($arr_styles))))
                ->setPays($this->getReference('pays'.mt_rand(1,count($arr_pays))));
        $manager->persist($artist);

        $artist = new Artiste();
        $artist->setNom($faker->lastName())
                ->setDescription($faker->paragraphs(2, true))
                ->setImage('https://randomuser.me/api/portraits/'.$faker->randomElement($genres).'/2.jpg')
                ->setType($faker->randomElement($types))
                ->setSiteWeb($faker->url())
                ->setStyle($this->getReference('style'.mt_rand(1,count($arr_styles))))
                ->setPays($this->getReference('pays'.mt_rand(1,count($arr_pays))));
        $manager->persist($artist);

        $artist = new Artiste();
        $artist->setNom($faker->lastName())
                ->setDescription($faker->paragraphs(2, true))
                ->setImage('https://randomuser.me/api/portraits/'.$faker->randomElement($genres).'/3.jpg')
                ->setType($faker->randomElement($types))
                ->setSiteWeb($faker->url())
                ->setStyle($this->getReference('style'.mt_rand(1,count($arr_styles))))
                ->setPays($this->getReference('pays'.mt_rand(1,count($arr_pays))));
        $manager->persist($artist);

        $artist = new Artiste();
        $artist->setNom($faker->lastName())
                ->setDescription($faker->paragraphs(2, true))
                ->setImage('https://randomuser.me/api/portraits/'.$faker->randomElement($genres).'/4.jpg')
                ->setType($faker->randomElement($types))
                ->setSiteWeb($faker->url())
                ->setStyle($this->getReference('style'.mt_rand(1,count($arr_styles))))
                ->setPays($this->getReference('pays'.mt_rand(1,count($arr_pays))));
        $manager->persist($artist);

        $artist = new Artiste();
        $artist->setNom($faker->lastName())
                ->setDescription($faker->paragraphs(2, true))
                ->setImage('https://randomuser.me/api/portraits/'.$faker->randomElement($genres).'/5.jpg')
                ->setType($faker->randomElement($types))
                ->setSiteWeb($faker->url())
                ->setStyle($this->getReference('style'.mt_rand(1,count($arr_styles))))
                ->setPays($this->getReference('pays'.mt_rand(1,count($arr_pays))));
        $manager->persist($artist);
        
        $manager->flush();
    }
}
