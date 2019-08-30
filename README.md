# Easy PHP CSV
A PHP class to make creating, manipulating, merging, saving and downloading CSV files (and data) and simple as possible.

## Specific Functionality

- Open existing CSV files, output data as a string or an array.
- Create new CSV files from Arrays, Strings or both.
- Merge CSV files, Strings or both.
- Download a CSV straight to browser.
- Store CSV files or simply manipulate in memory.
- All CSV Data is made UTF8 safe.

- Set Deliminator
- Set EOL
- Set Enclosure

The class supports chaining, while its not used in these examples for clarity you can create, set a name, and store a file in one chained request.

## Notes

- This class is heavy on memory for large files as it does not currently support line by line reading (TODO).
- there are no known bugs, please report any that you find.

## Installation

Install with composer, this is not in packagist currently so you will need to add the following repository to your composer Json file.

```
{
  "repositories": [{
    "type": "composer",
    "url": "https://codestore.sc.vg"
  }]
}
```
Then run:

`composer require apexl/easy-csv dev-master`

To use, make sure you include the vendor autoload file and add the use statement:

```php
<?php
use apexl\EasyCSV;
```

## Usage Examples

### Open an existing CSV file
```php
$eaCSV = new EasyCSV();
//we assume that by default, CSV files will have a header row as the first row. If a file has no headers, pass FALSE as the second argument.
$eaCSV->loadFromFile($pathToFile);
```

### Accessing data in various formats

```php
//get the data as an array of rows. Each row is indexed by headers (assuming the opened file has them)
$array = $eaCSV->csvArray;
//get the full csv string
$string = $eaCSV->csvString;
//get the name of the currently loaded file
$fname = $eaCSV->loadedFilename
```

### Creating a CSV File

```php
$eaCSV = new EasyCSV();

//create a CSV from array.
//we assume headers are in $data[0].
$eaCSV->arrayToCsv($data);
//to create a CSV from seperate rows and header data:
$eaCSV->arrayToCsv($rows, $headers, FALSE);
//finally to create a CSV with no headers at all:
$eaCSV->arrayToCsv($rows, [], FALSE);

//To create a CSV file from a string - NOTE when i get a chance, this will be better named.
$eaCSV->csvStringToArray($string);
//If the string does not contain header data:
$eaCSV->csvStringToArray($string, FALSE);
```

### Downloading a CSV to browser

```php
$eaCSV = new EasyCSV();
//we're loading a file for this example, but so long as there is data in the class you can download.
$eaCSV->loadFromFile($pathToFile);
$eaCSV->downloadCsv();
//Call Exit to force execution end. you MUST do this for it to work.
exit;
```

### Saving a CSV file to a specific path

```php
$eaCSV = new EasyCSV();
//we're using a csv created from an array for this example, but so long as there is data in the class you can store
$eaCSV->arrayToCsv($data);
//set the name of the file you want to store.
$eaCSV->setFileName('export.csv');
//set the file path to store it to. Exclude the filename.
$eaCSV->setStorePath('/var/www/mysite/exports');
//Store the file
$eaCSV->store();

//You can chain these requests
$eaCSV = new EasyCSV();
$eaCSV->arrayToCsv($data)->setFileName('export.csv')->setStorePath('/var/www/mysite/exports')->store();
//download for good measure (you dont need to do this!)
$eaCSV->downloadCsv();
exit;
```

### Merge CSV files and Data

```php
// merge csv strings. NOTE if both strings have headers, they will be merged in as well.
//TODO add handling for merging, ignoring or changing headers in a merge.

//The first arg is always the filename. Any additional arguments are appended together.
//The method will accept Strings or Streams. NOTE - the php output buffer (php://output) does not support stream wrappers.
$mergedCsv = EasyCSV::mergeFiles('export.csv', $csvStringOne, $csvStringTwo);
//for storing this
$mergedCsv->setStorePath($filePath);
$mergedCsv->store();
//to download:
$mergedCsv->downloadCsv();
exit;
```
