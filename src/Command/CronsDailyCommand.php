<?php

namespace App\Command;

use App\Entity\BankTransactions;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CronsDailyCommand extends Command
{
    protected static $defaultName = 'crons:daily';

    // 2. Expose the EntityManager in the class level
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        // 3. Update the value of the private entityManager variable through injection
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Run the daily cron every day at 12:01 am')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        // DAILY COMMANDS:
        // Update Days on Subscriptions + Remove Subscribed User Role, Daily Interest in Bank, Shows Reset
        $em = $this->entityManager;

        // USER COMMANDS
        $uRepo = $em->getRepository("App:User");
        $uRes = $uRepo->findAll();

        foreach ($uRes as $user) {


            $id = $user->getId();

            $output->writeln($id);

            // Calculate upgrade days remaining
            $currentDays = $user->getDays();
            $interest = $user->getInterest();
            $currentBank = $user->getBank();

                $days = $currentDays;

                $addInterest = $currentBank * ($interest/100);
                $newBank = $currentBank + $addInterest;

                // Do not remove days from anyone with $currentDays == 999. Everyone else loses 1 day.
                if ($currentDays != 999 && $currentDays > 0) {
                    $days = $currentDays - 1;
                }

                // If the new value is 0 days, we need to make sure they are now "ROLE_USER" and lose their upgrade. Remove that tag from Klaviyo
                // Set shows and interest as appropriate
                #TODO Klaviyo update on loss of upgrade
                if ($days <= 0) {
                    $user->setDays(0);
                    $user->setRoles(array('ROLE_USER'));
                    $user->setShows(5);
                    $user->setInterest(0.15);
                }
                elseif ($days > 0) {
                    $user->setDays($days);
                    $user->setShows(20);
                    $user->setInterest(0.25);
                }

            if ($addInterest > 0) {
                $bank = new BankTransactions();
                $user->setBank($newBank);
                $now = date('Y-m-d H:i:s');
                $bank->setTxnDate(new DateTime($now));
                $bank->setAmount($addInterest);
                $bank->setToUser($id);
                $bank->setFromUser($id);
                $bank->setDescription("Daily interest deposit");
                $bank->setType("Deposit");
            }

            $em->persist($bank);
            $em->flush();

        } // End user commands

        $io = new SymfonyStyle($input, $output);

        $io->success('You got some shit done!');

        return Command::SUCCESS;

        return Command::FAILURE;
    }

}
