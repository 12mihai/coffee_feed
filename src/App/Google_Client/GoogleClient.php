<?php
namespace App\Google_Client;

class GoogleClient {

    const CREDENTIALS_FILE_PATH = 'config/credentials.json';
    const TOKEN_FILE_PATH = 'config/token.json';

    /**
     * Initialize Google API Client
     *
     * @param string $title Spreadsheet Title
     * @param array $values XML formatted array
     * @return \Google_Service_Sheets_UpdateValuesResponse
     * @throws \Google_Exception
     */
    public function initializeClient($title, $values) {
        // Get the API client and construct the service object.
        $client = self::getClient();
        $service = new \Google_Service_Sheets($client);
        $spreadsheet = new GoogleSpreadsheets($service);

        $spreadsheetId = $spreadsheet->create($title);

        $options = array('valueInputOption' => 'RAW');

        $body   = new \Google_Service_Sheets_ValueRange(['values' => $values]);

        $response = $service->spreadsheets_values->update($spreadsheetId, 'A1', $body, $options);

        return $response;

    }

    /**
     * Returns an authorized API client.
     *
     * @return \Google_Client
     * @throws \Google_Exception
     */
    protected function getClient()
    {
        $client = new \Google_Client();
        $client->setApplicationName('XML to Google Sheets');
        $client->setScopes(\Google_Service_Sheets::SPREADSHEETS);
        $client->setAuthConfig(self::CREDENTIALS_FILE_PATH);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = self::TOKEN_FILE_PATH;
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }
}

