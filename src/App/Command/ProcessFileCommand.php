<?php
namespace App\Command;

use App\Google_Client\GoogleClient;
use App\Google_Client\GoogleSpreadsheets;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessFileCommand extends Command
{
    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            //command name
            ->setName('app:process-file')

            // command description
            ->setDescription('Processes an XML file.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to process an XML file into a Google Sheet.')
        ;
    }

    /**
     * Convert XML to Spreadsheet
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $filePathQuestion = new Question("Choose XML filepath (local or remote) ", 'files/coffee_feed_short.xml');
        $filePath = $helper->ask($input, $output, $filePathQuestion);
        $xmlToArray = self::xmlToArray($filePath);

        $formattedArray = self::createFormattedArray($xmlToArray);

        $spNameQuestion = new Question("Enter a name for the Spreadsheet: ", 'Coffee');
        $spreadsheetName = $helper->ask($input, $output, $spNameQuestion);
        $spreadsheetId = GoogleClient::initializeClient($spreadsheetName, $formattedArray);

        $message = sprintf("Spreadsheet ID is %s!", $spreadsheetId);

        $output->writeln($message);

        return 0;
    }

    /**
     * Convert xml file to array
     *
     * @param string $xmlFile The raw XML file
     * @return array
     */
    protected function xmlToArray($xmlFile) {
        $xml = simplexml_load_file($xmlFile, null, LIBXML_NOCDATA);
        $json = json_encode($xml);
        $createdArray = json_decode($json,TRUE);
        return $createdArray;
    }

    /**
     * Format raw array to the required structure
     *
     * @param array $rawArray The Array converted from XML
     * @return array The formatted array based on spreadsheet structure
     */
    protected function createFormattedArray($rawArray) {
        $formattedArray = array();
        $arrayKey = 0;
        $headers = array_keys($rawArray['item'][0]);
        $formattedArray[$arrayKey] = $headers;

        foreach ($rawArray['item'] as $item) {
            foreach ($item as $key => $value) {
                if (empty($value)) {
                    $item[$key] = '';
                }
            }
            $arrayKey++;
            $formattedArray[$arrayKey] = array_values($item);
        }

        return $formattedArray;
    }
}
