<?php

require_once ('src/Helpers/ClsSitename.php');
require_once ('src/Helpers/GenerateReport.php');
class SalesReport
{
    /**
     * @var ValidationPropertyArgs
     */
    private ValidationPropertyArgs $validationPropertyArgs;

    /**
     * @var mysqli
     */
    private mysqli $mysqli;

    /**
     * @var mysql
     */
    private Mysql $mysql;

    /**
     * @var Log
     */
    private Log $log;

    /**
     * @var array
     */
    private array $arguments;

    public function __construct(
        ValidationPropertyArgs $validationPropertyArgs,
        mysqli $mysqli,
        array $arguments,
        Mysql $mysql,
        Log $log
    )
    {
        $this->validationPropertyArgs = $validationPropertyArgs;
        $this->mysqli = $mysqli;
        $this->arguments = $arguments;
        $this->mysql = $mysql;
        $this->log = $log;
    }

    public function run(): void
    {

        $validationErrors = $this->validationPropertyArgs->validate($this->arguments);

        if (count($validationErrors)) {
            $fileDir = 'logs/' . gmdate('Y-m-d:h:i:s \G\M\T', time()) . '.txt';
            $this->log->setDir($fileDir);
            $this->log->setLog($validationErrors[0]);
            $this->log->createLog();
            trigger_error($validationErrors[0], E_USER_WARNING);
            return;
        }

        $sales = $this->mysql->getSalesDto();

        $neatSiteNames = [];
        $counts = [];
        $examples = [];

        foreach ($sales as $index => $sale) {

            $obj = new ClsSiteName($sale['property_url_eg'], $sale['sitecode_eg']);
            $neatSiteName = $obj->getSiteName();
            $neatSiteNames[] = $neatSiteName;
            $example["loader_id_egs"] = $sale['loader_id_egs'];
            $example["sitecode_eg"] = $sale['sitecode_eg'];
            $example["property_url_eg"] = $sale['property_url_eg'];
            $examples[] = $example;
            $counts[] = $sale['allCount'];
        }

        $report = new GenerateReport();
        $report->setNeatSiteName($neatSiteNames);
        $report->setCount($counts);
        $report->setExamples($examples);
        $report->generateHtmFile('sales');
    }

}