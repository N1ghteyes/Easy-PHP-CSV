# Easy PHP CSV
PHP CSV class to make creating and outputting CSV files and simple as possible. No Dependancies.

## Usage Examples
First, include the class and make a default data array.
  ```php
  include('Easy-PHP-CSV/eacsv.php'); //include the class. This will be namespaced at some point.
  
  //Build our demo csv array of arrays. keys are ignored for now so can be numeric or string.
  //Each array is a row containing column data.
  $demo = array(
    array('column1', 'column2', 'column3', 'column4', 'column5'), //Row 1
    array('column1', 'column2', 'column3', 'column4', 'column5'), //Row 2
  );
  ```
  
Output a CSV file with no headers straight to the browser (file download)
  ```php
  $eacsv = new eacsv($demo);
  $eacsv->getCsv(); // By default, the csv filepath is set to 'php://output' which passes the file straight to the browser as export.csv
  ```

Output a CSV file with headers straight to the browser (file download)
```php
//headers are simple a one row array.
$headers = ['col Header 1', 'col Header 2', 'col Header 3', 'col Header 4', 'col Header 5']; //array of headers.
$eacsv = new eacsv($demo, $headers);
$eacsv->getCsv(); // By default, the csv filepath is set to 'php://output' which passes the file straight to the browser as export.csv
```

Output a CSV file with no headers, tab deliminated straight to the browser (file download). 
```php
$eacsv = new eacsv($demo, array(), '\t'); //NOTE the class will handle the following for Tab delimination: '\t', "\t", "tab" 
$eacsv->getCsv(); // By default, the csv filepath is set to 'php://output' which passes the file straight to the browser as export.csv
```

Save a CSV file to a directory and get the filepath.
```php
$eacsv = new eacsv($demo, array(), ',', 'mycsv.csv', '/downloads/exports'); //results in example.com/downloads/exports/mycsv.csv
$eacsv->saveCsv(); //getCsv also works here, and will return a string of data. use saveCsv if you dont need the string.
```

Get the contents of an existing CSV file called 'mycsv.csv' as a string
```php
$eacsv = new eacsv($demo, array(), ',', 'mycsv.csv', '/downloads/exports'); //results in example.com/downloads/exports/mycsv.csv
$csvstring = $eacsv->getCsv()->csvstring; //getCsv also works here, and will return a string of data. use saveCsv if you dont need the string.
```
