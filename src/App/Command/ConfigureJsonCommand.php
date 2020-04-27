<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigureJsonCommand extends Command
{
    const JSON_FILE_PATH = 'config/credentials.json';

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

        $credentials = [
            "client_id" => $clientId,
            "project_id" => "quickstart-1587929780297",
            "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
            "token_uri" => "https://oauth2.googleapis.com/token",
            "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
            "client_secret" => $clientSecret,
            "redirect_uris" => ["urn:ietf:wg:oauth:2.0:oob", "http://localhost"],
        ];

        $jsonCredentials = json_encode(array('installed' => $credentials));

        if (file_put_contents(self::JSON_FILE_PATH, $jsonCredentials))
            $message = sprintf("Success! Credentials are stored in %s!", self::JSON_FILE_PATH);
        else
            $message = "Oops! Something went wrong encoding the credentials.";

        $output->writeln($message);

        return 0;
    }
}
