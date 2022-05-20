<?php

require_once ('src/Helpers/Log.php');
class Mysql
{

    /**
     * @var mysqli
     */
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    /**
     * @return array
     */
    public function getSalesDto(): array
    {
        $result = [];

        $sql = "select SUBSTRING_INDEX(SUBSTRING_INDEX(replace(property_url, concat(substring_index(property_url, '//', 1), '//'), ''), '/', 1), '?', 1) as host,
  count(*) as allCount,
  substring_index(group_concat(house_loader_id order by house_loader_id desc), ',', 3) as loader_id_egs, 
  substring_index(group_concat(property_url order by house_loader_id desc), ',', 1) as property_url_eg, 
  substring_index(group_concat(sitecode order by house_loader_id desc), ',', 1) as sitecode_eg
from site_house 
where current = 1 
and property_url is not null
group by 1;";

        $query = $this->mysqli->query($sql);

        while ($row = mysqli_fetch_assoc($query)) {
            $result[] = $row;
        }

        $this->createLog($sql);

        return $result;
    }

    /**
     * @return array
     */
    public function getRentalDto(): array
    {
        $result = [];

        $sql = "select SUBSTRING_INDEX(SUBSTRING_INDEX(replace(property_url, concat(substring_index(property_url, '//', 1), '//'), ''), '/', 1), '?', 1) as host,
  count(*) as allCount,
  substring_index(group_concat(rental_loader_id order by rental_loader_id desc), ',', 3) as loader_id_egs, 
  substring_index(group_concat(property_url order by rental_loader_id desc), ',', 1) as property_url_eg, 
  substring_index(group_concat(sitecode order by rental_loader_id desc), ',', 1) as sitecode_eg
from site_rental 
where property_url is not null
group by 1;";

        $query = $this->mysqli->query($sql);

        while ($row = mysqli_fetch_assoc($query)) {
            $result[] = $row;
        }

        $this->createLog($sql);

        return $result;
    }

    /**
     * @param string $sql
     */
    private function createLog(string $sql)
    {
        $fileDir = 'logs/' . gmdate('Y-m-d:h:i:s \G\M\T', time()) . '.txt';

        $log = new Log();
        $log->setDir($fileDir);
        $log->setLog($sql);
        $log->createLog();
    }
}