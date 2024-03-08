<?php

require_once('vendor/autoload.php');

//This section includes necessary files, sets up the WebDriver server URL, and creates a new instance of the Chrome browser using RemoteWebDriver.
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

// Chromedriver (if started using --port=4444 as above)
$serverUrl = 'http://localhost:4444';

// Chrome
//The script navigates the browser to the specified URL.
$driver = RemoteWebDriver::create($serverUrl, DesiredCapabilities::chrome());
$driver->get('https://kempsey.greenlightopm.com/search-register?deptName=Development');

// Wait for the search results
//The script waits for up to 10 seconds for an element matching the specified CSS selector to be present on the page. This selector targets the rows of a table.
$driver->wait(10)->until(
    WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('table.table.table-striped.searchTable.dataTable.no-footer tbody tr'))
);


//The script finds all rows in the table and iterates through them.
//For each row, it finds the individual columns (td elements) within that row.
//If the number of columns is 5, it assumes a specific structure and extracts data from each column.
//The extracted data is then displayed on the terminal using echo.
// Locate the table rows
$rows = $driver->findElements(WebDriverBy::cssSelector('table.table.table-striped.searchTable.dataTable.no-footer tbody tr'));

// Loop through rows
foreach ($rows as $row) {
    // Find the elements in the row
    $columns = $row->findElements(WebDriverBy::cssSelector('td'));

    // Check the number of columns
    if (count($columns) == 5) {
        // Order the columns and retrieve their text if they exist
        $applicationNumber = $columns[0]->findElement(WebDriverBy::cssSelector('a.blueLink'))->getText();
        $created = $columns[1]->getText();
        $description = $columns[2]->getText();
        $properties = $columns[3]->getText();
        $status = $columns[4]->getText();

        echo "Application #: $applicationNumber" . PHP_EOL;
        echo "Created : $created" . PHP_EOL;
        echo "Description : $description" . PHP_EOL;
        echo "Properties : $properties" . PHP_EOL;
        echo "Status : $status" . PHP_EOL;

        echo PHP_EOL;
    } else {
        // Handle unexpected number of columns
        echo "Unexpected number of columns: " . count($columns) . PHP_EOL;
    }
}

//Finally, the script closes the browser session.
$driver->quit();
