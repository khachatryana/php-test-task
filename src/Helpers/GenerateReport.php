<?php

class GenerateReport
{

    /**
     * @var array
     */
    private array $neatSiteName;

    /**
     * @var array
     */
    private array $count;

    /**
     * @var array
     */
    private array $examples;


    /**
     * @param array $neatSiteName
     */
    public function setNeatSiteName(array $neatSiteName): void
    {
        $this->neatSiteName = $neatSiteName;
    }

    /**
     * @param array $examples
     */
    public function setExamples(array $examples): void
    {
        $this->examples = $examples;
    }

    /**
     * @param array $count
     */
    public function setCount(array $count): void
    {
        $this->count = $count;
    }

    public function generateHtmFile($dir)
    {
        $content = '
       <html>
         <head>
           <title>sample_epcs_sales</title>
         </head>
         <body>
          <h1>sample_epcs_sales</h1>
          <h3>Description</h3>For each sitecode in house_loader where epc_link can be set and current > 0,<br>select 10 random rows where epc_link is set and current > 0.<br>Print the parent URL and the epc URL.
          <h3>Instructions</h3>Please report epc_links which lead to non-EPC content - so a URL or content pattern can be added to Robot.pm::@external_link_stop_list and a db update can be run to remove existing bad EPCs.<br>Please report whether the epc_link is still included on the page with an explanation of how to find it.<br>N.B. epc checks should relate directly to/from the property, with epc data embedded in non-epc linked docs ignored.Where bad content is found, check the cached content to see what has been downloaded.Where multiple EPC links appear on the URL page, check the system is recording the EPC PDF link ahead of any other types (eg EIRs): if not, please report.<br>Please include details of each finding reported.<br>
          <h2>Results</h2>
          <table border=1 style="white-space:nowrap" width="240">
           <tr>
            <th align="left">neat site name</th>
            <th align="left">total count</th>
            <th align="left">examples</th>
           </tr>
        ';

        foreach ($this->neatSiteName as $index => $item){

            $loaderEgsArray = explode(",", $this->examples[$index]["loader_id_egs"]);
            $tableContent = '';
            foreach ($loaderEgsArray as $index => $single){
                $tableContent .= '
                  <table>
                 <tr>
                     <td>house loader id:</td>
                     <td>
                        '.$single.'
                     </td>
                 </tr>
                 <tr>
                     <td>site code:</td>
                     <td>
                      '.$this->examples[$index]["sitecode_eg"].'
                     </td>
                 </tr>
                  <tr>
                     <td>property url:</td>
                     <td>
                      <a target="_blank" href="'.$this->examples[$index]["property_url_eg"].'">  '.$this->examples[$index]["property_url_eg"].'</a>
                     </td>
                 </tr>
                </table>
                <hr />
                ';
            }

            $content .= '
                  <tr>
            <td>'.$item.'</td>
            <td>'.$this->count[$index].'</td>
            <td>
              '.$tableContent.'
            </td>
           </tr>
            ';
        }

        $content .= '
         </table>
         </body>
        </html>';

        $fileDir = 'src/Reports/'.$dir.'/'.gmdate('Y-m-d:h:i:s \G\M\T', time()).'.htm';
        $reportFile = fopen($fileDir, "w");
        fwrite($reportFile, $content);
        fclose($reportFile);
    }
}