<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Project;
use App\Entity\Skill;
use App\Entity\Lang;
use App\Entity\Environment;
use App\Entity\Hobbies;
use App\Entity\Job;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('adminGuiCV')
            ->setEmail('guillaume.vigneres@gmail.com')
            ->setPassword($this->passwordHasher->hashPassword($user, 'Nougat!!2006'))
            ->setDateBirthday(new \Datetime(1986-5-4))
            ->setDateUpdate(new \Datetime)
            ->setPhone('0671075551')
            ->setCity('Tarbes')
            ->setRoles(['ROLE_ADMIN'])
            ->setContent('text description')
            ->setAvatar('avatar.png')
            ->setCvFile('CV_GUILLAUME_VIGNERES.pdf');
        $manager->persist($user);

        for ($i=0; $i<6; $i++)
        {
            $project = new Project();

            $project->setTitleProject("titre")
                ->setContentProject("test")
                ->setDateCreateProject(new \Datetime)
                ->setDateUpdateProject(new \Datetime)
                ->setIsActive(true)
                ->setImageProject('keyboard.jpg');
            $manager->persist($project);
        }

        for ($i=0; $i<5; $i++)
        {
            $skill = new Skill();

            $skill->setSkillName("html")
                ->setPurcentSkill('65');
            $manager->persist($skill);
        }

        for ($i=0; $i<5; $i++)
        {
            $environment = new Environment();

            $environment->setEnvironmentName("git")
                        ->setImageEnvironment('icons8-git-48.png');
            $manager->persist($environment);
        }

        $lang = new Lang();
        $lang->setLangName('anglais')
            ->setPurcentLang('65');
        $manager->persist($lang);

        for ($i=0; $i<5; $i++)
        {
            $hobbies = new Hobbies();

            $hobbies->setHobbiesName("sport");
            $manager->persist($hobbies);
        }


        for ($i=0; $i<5; $i++)
        {
            $job = new Job();

            $job->setTitleJob("dev back")
                ->setContentJob("test")
                ->setDateBeginJob(new \Datetime)
                ->setDateFinishJob(new \Datetime)
                ->setPlaceJob("tarbes")
                ->setEnterpriseJob("nomEntreprise");
            $manager->persist($job);
        }

        $manager->flush();
    }
}
