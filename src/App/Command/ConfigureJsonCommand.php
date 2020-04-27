<?php
namespace App\Command;

use App\LogDependency;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigureJsonCommand extends Command
{
    /**
     * Command configuration
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            //command name
            ->setName('app:configure-json')
            // command description
            ->setDescription('Creates GoogleSheets API credentials configuration.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Get the credentials from "https://developers.google.com/sheets/api/quickstart/php?authuser=2#step_1_turn_on_the"');
    }

    /**
     * Create credentials file
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $clientIdQuestion = new Question("Enter API Client ID:");
        $clientId = $helper->ask($input, $output, $clientIdQuestion);

        $clientSecretQuestion = new Question("Enter API Client Secret:");
        $clientSecret = $helper->ask($input, $output, $clientSecretQuestion);

        $logger = new ConsoleLogger($output);
        $logDependency = new LogDependency($logger);
        $message = $logDependency->executeConfigureJsonCommand($clientId, $clientSecret);

        $output->writeln($message);

        return 0;
    }
}
