<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use App\Entity\PhotoService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class PhotoServiceExpireCommand extends ContainerAwareCommand
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
        //INTERVAL IS SET TO 30 DAYS (after 30 days remove folders)
        $interval = new \DateInterval('P30D');
        $now->sub($interval);
        $startDateTime = \DateTime::createFromFormat('U', ($now->format('U') ));
        $startDateTime->format("Y-m-d H:i:s");
        
        $doctrine = $this->getContainer()->get('doctrine');

        $em = $doctrine->getEntityManager();
        
        $photoService = $em->getRepository(PhotoService::class)->deleteExpiredFolders($startDateTime);
        
        //$photoService = $em->getRepository(PhotoService::class)->deleteExpiredFolders($startDateTime);

        //AFTER 30 DAYS REMOVE FOLDERS
        //SET FOLDERS TO EMPTY IN TABLE
        if ($photoService)
        {
            $filesystem = new Filesystem();
            foreach($photoService as $p){
                $filesystem->remove(['../public_html/upload/photo_service/'.$p->getFolder()]);
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