<?php
namespace App;

use Psr\Log\LoggerInterface;

class LogDependency
{
    const JSON_FILE_PATH = 'config/credentials.json';

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Delete the credential configurations and log the errors
     *
     * @param string $confirmDeleteAnswer Confirmation answer
     * @return string
     */
    public function executeDeleteConfigurationCommand($confirmDeleteAnswer)
    {
        if ($confirmDeleteAnswer === "yes")
            $deleted = self::deleteConfigurationFiles();
        else
            $deleted = false;

        if ($deleted) {
            return "Configuration file(s) were removed successfully";
        } else {
            $this->logger->error("Configuration files could not be deleted.");
        }

        return "Oops! Something went wrong, check logs.";
    }

    public function executeConfigureJsonCommand($clientId, $clientSecret)
    {
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
            return sprintf("Success! Credentials are stored in %s!", self::JSON_FILE_PATH);
        else
            $this->logger->error("Oops! Something went wrong encoding the credentials.");

        return "Oops! Something went wrong, check logs.";
    }
}
