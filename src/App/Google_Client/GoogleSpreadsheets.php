<?php
namespace App\Google_Client;

class GoogleSpreadsheets
{
    public function __construct($service)
    {
        $this->service = $service;
    }

    public function create($title)
    {
        $service = $this->service;

        $spreadsheet = new \Google_Service_Sheets_Spreadsheet([
            'properties' => [
                'title' => $title
            ]
        ]);
        $spreadsheet = $service->spreadsheets->create($spreadsheet, [
            'fields' => 'spreadsheetId'
        ]);

        return $spreadsheet->spreadsheetId;
    }
}
