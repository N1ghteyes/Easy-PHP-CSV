<?php

/**
 * PHP class to easily handle CSV files
 *
 * @author N1ghteyes - www.source-control.co.uk
 * @copyright 2016 N1ghteyes
 * @license license.txt The MIT License (MIT)
 * @link https://github.com/N1ghteyes/Easy-PHP-CSV
 *
 * @TODO: many many things. including finish up and produce a release.
 * @TODO: much tidying, streamlining and otherwise sanitizing of code.
 */
class eacsv
{

    private $path; //path location for csv file pointer.
    private $cp; //csv File pointer storage
    private $deliminator; // Deliminator - not changeable after class is instantiated.

    public $filename;

    /**
     * Constructor function - pass all info for the csv, none or somewhere in the middle.
     * @param array $csvRowData
     * @param $csvHeadData
     * @param string $deliminator
     * @param string $path
     */
    public function __construct($csvRowData = array(), $csvHeadData = array(), $deliminator = ',', $filename = 'export.csv', $path = 'php://output')
    {
        //set defaults
        $this->deliminator = $this->_setDeliminator($deliminator);
        $this->path = $path;
        $this->filename = $filename;
        $this->_setExportHeaders();

        $this->cp = fopen($this->path, 'c+'); //open a new file, the pointer is automatically placed at the beginning.
        if($this->path != 'php://output') {
            fseek($this->cp, 0, SEEK_END); // move the pointer to the end of the file opened.
        }
        if (!empty($csvHeadData)) { //assume we're adding a header regardless of file contents
            $this->_processHeader($csvHeadData, TRUE);
        }
        if (!empty($csvRowData)) { //if we've passed in array data, add csv data to the file..
            $this->_processArrays($csvRowData);
        }

        return $this;
    }

    /**
     * allows for lazy / silly deliminators. - because why not.
     * @param $deliminator
     * @return string
     */
    private function _setDeliminator($deliminator)
    {
        switch ($deliminator) {
            case 'comma':
            case 'commer':
                $delim = ',';
                break;
            case 'tab':
            case '\t':
                $delim = "\t";
                break;
            default:
                $delim = $deliminator;
        }
        return $delim;
    }

    /**
     * @param $csvHead
     * @param bool|FALSE $start
     * @todo - actually test this works..
     */
    private function _processHeader($csvHead, $start = FALSE)
    {
        if ($start) { //if start is true, add a line to the start of the csv. To do this, we need to create copy any existing data and recreate the file.
            $pos = fgets($this->cp);
            if ($pos != FALSE) { //if false, or 0 we can ignore this.
                $initialfile = file_get_contents($this->path); //load the file contents
                $this->cp = fopen($this->path, 'c+'); //open a new file.
                fputcsv($this->cp, $csvHead); //write the header to the top of the new file
                fwrite($this->cp, $initialfile); //add the old file onto the end of the header.
            }
        } else {
            fputcsv($this->cp, $csvHead, $this->deliminator);
        }
    }

    /**
     * @param $csvArray
     * @return $this
     */
    private function _processArrays($csvArray)
    {
        $rows = count($csvArray); //count the rows, allows usage of for loops - much faster than foreach in this context.
        $keys = array_keys($csvArray); //handle non numeric, non 0 arrays.
        for ($i = 0; $i < $rows; ++$i) {
            fputcsv($this->cp, $csvArray[$keys[$i]], $this->deliminator);
        }
        return $this;
    }

    private function _setExportHeaders(){
        if($this->path == 'php://output') {
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=' . $this->filename);
            header('Pragma: no-cache');
        }
    }

    /**
     * Function to handle the return of the CSV file.
     * @return $this
     */
    public function getCsv()
    {
        fclose($this->cp); //we're outputting to browser so just close the pointer.
        return $this;
    }

    public function saveCsv()
    {

    }

    /**
     * @param $rows
     */
    public function addRows($rows)
    {
        $this->_processArrays($rows);
        return $this;
    }

}
