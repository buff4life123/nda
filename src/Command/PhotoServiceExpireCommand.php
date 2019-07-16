<?php
namespace App\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
//use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use App\Entity\PhotoService;

class PhotoServiceExpireCommand extends Command
{
    protected function configure()
    {
        $this->setName('app:remove-folder')
        ->setDescription('Execute to check if folder storage period has ended.')
        ->setHelp("Remove the folder");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Execute Cron: Remove-folder by Date',
            '======================================',
            '',
        ]);

        $now = new \DateTime('now');
        //INTERVAL IS SET TO 8 DAYS (after 8 days remove folders)
        $interval = new \DateInterval('P8D');
        $now->sub($interval);
        $startDateTime = \DateTime::createFromFormat('U', ($now->format('U') ));
        $startDateTime->format("Y-m-d H:i:s");
        
        $doctrine = $this->getContainer()->get('doctrine');

        $em = $doctrine->getEntityManager();
        
        $photoService = $em->getRepository(PhotoService::class)->deleteExpiredFolders($startDateTime);
        
        //$photoService = $em->getRepository(PhotoService::class)->deleteExpiredFolders($startDateTime);

        //AFTER 8 DAYS REMOVE FOLDERS
        //SET FOLDERS TO EMPTY IN TABLE
        if ($photoService)
        {
            $path = $this->getContainer()->get('kernel')->getProjectDir().'/public_html/upload/photo_service/';

            $filesystem = new Filesystem();
            foreach($photoService as $p){
                $file = $p->getFolder().'.zip';
                $filesystem->remove([$path.$file]);

                $file = $p->getFolder();
                $filesystem->remove([$path.$file]);

                $p->setFolder('');
                $em->persist($p);
                
                if($p->getGdpr() != 1){
                    $em->remove($p);
                }
            }
            $em->flush();

          
        }
        // outputs a message followed by a "\n"
        
        $output->writeln( count($photoService). ' folders removed with sucess !');       
    }

}