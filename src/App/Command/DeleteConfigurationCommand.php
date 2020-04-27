<?php
namespace App\Command;

use App\LogDependency;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteConfigurationCommand extends Command
{
    const CREDENTIALS_FILE_PATH = 'config/credentials.json';
    const TOKEN_FILE_PATH = 'config/token.json';

    /**
     * Command configuration
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            //command name
            ->setName('app:delete-credentials')
            // command description
            ->setDescription('Removes GoogleSheets API credentials.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Removes credentials.json and token.json files');
    }

    /**
     * Prompt for delete confirmation
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $confirmDeleteQuestion = new Question("Are you sure you want to delete(yes/no):");
        $confirmDeleteAnswer = $helper->ask($input, $output, $confirmDeleteQuestion);

        $logger = new ConsoleLogger($output);
        $logDependency = new LogDependency($logger);
        $message = $logDependency->executeDeleteConfigurationCommand($confirmDeleteAnswer);

        $output->writeln($message);

        return 0;
    }

    /**
     * Delete configuration files
     *
     * @return bool
     */
    protected function deleteConfigurationFiles() {
        $filesDeleted = true;

        if (file_exists(self::CREDENTIALS_FILE_PATH)) {
            $filesDeleted = unlink(self::CREDENTIALS_FILE_PATH);
        }
        if (file_exists(self::TOKEN_FILE_PATH)) {
            $filesDeleted = unlink(self::TOKEN_FILE_PATH);
        }

        return $filesDeleted;

    }
}
