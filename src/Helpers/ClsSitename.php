<?php

require_once ('config/Constant.php');
require_once ('src/Helpers/HomeCoFuncs.php');
class ClsSiteName
{
    /**
     * @var string
     */
    private string $siteName;

    /**
     * @var string
     */
    private string $proxySiteName;

    /**
     * @var Constant
     */
    private Constant $constant;

    /**
     * @var HomeCoFuncs
     */
    private HomeCoFuncs $homeCoFuncs;

    public function __construct($propertyUrl, $sitecode) {
        ### BTH 24/Mar/10 track when propertyurl is not known
        ### seems to cope as can deduce from the sitecode
        if (! $propertyUrl) {
            #logerror(ERROR_SEVERE, "csn233", '$propertyUrl not set in class Sitename constructor');
        }

        $this->constant = new Constant();

        $this->homeCoFuncs = new HomeCoFuncs();

        $bits = parse_url($propertyUrl);
        if (($sitecode == $this->constant->getOODLESITECODE())&&(isset($bits['query']))) {
            $params = parse_query($bits['query']);
            if ($params['u']) {
                if (isset($bits['host'])) {
                    $this->proxySiteName = $this->getSitenameFromHost($bits['host']);
                } else {
                    $this->proxySiteName = $this->getSitenameFromSitecode($sitecode);
                }
                $bits = parse_url($params['u']);
                $sitecode = $this->getSitecodeFromUrl($params['u']);
            }
        }

        ### BTH 29/Mar/11 cope with findaproperty_upload urls eg http://anm.intelli-direct.com/e/b.dll?m=365&o=2590&c=0&url=http%3a%2f%2fwww.findaproperty.com%2fdisplayprop.aspx%3fedid%3d00%26salerent%3d0%26pid%3d8005421
        if (($sitecode == 'findaproperty_upload') && (isset($bits['query']))) {
            $params = parse_query($bits['query']);
            $bits = parse_url($params['url']);
            $sitecode = $this->getSitecodeFromUrl($params['url']);
        }

        if (isset($bits['host'])) {
            ### BTH 7/Jul/15 ensure that host is lowercase - but to avoid confusion apply only to host rather than whole url
            $this->siteName = $this->getSitenameFromHost(strtolower($bits['host']));
        } else {
            $this->siteName = $this->getSitenameFromSitecode($sitecode);
        }
    }

    /**
     * @return string
     */
    public function getSiteName(): string
    {
        return $this->siteName;
    }

    /**
     * @return string
     */
    public function getString(): string
    {
        $result = $this->siteName;
        if ($this->proxySiteName) {
            $result .= ' (via '.$this->proxySiteName.')';
        }
        return $result;
    }

    private function getSiteNameFromHost(string $orig_host) {
        $host = $orig_host;
        $prefixes = array('www', 'uk', 'search', 'portal', 'media', 'www\d+', 'srch', 'psrch', 'web', 'esrch',
            'www-q', 'powering', 'residential', '[\w\-]+\.sites', 'webcache', 'property', 'pdf', 'agents',
            'lc', 'resources', 'assets', 'details', 'webv\.svr\d*', 'images', 'crmsharepoint',
            'locationinformation', 'feeds', 'lettings', 'cms', 'tours', 'sales', 'player', 's\d+',
            'client', 'alto\d*\-live', 'auto', 'webcdn\d*', 'tv', 'services', 'docs', 'static',
            'webutils', 'fs\-\d+', 'payg', 'gibbsg\-dev', 'q', 'my', 'v\d+', 'agent', 'player',
            'premium');
        ### BTH 18/Jul/16 apply multiple loops to cope with eg alto-live.s3.amazonaws.com
        $loop_count = 0;
        while (1) {
            ### BTH 24/Jul/21 whoops: this code converts "property.xyz" to "xyz"
            ### so exit early if there's only one full stop in the host
            if (substr_count($host, ".") <= 1) {
                break;
            }

            $changed = 0;
            foreach ($prefixes as $prefix) {
                $trimmed_host = preg_replace('/^'.$prefix.'\./i', '', $host);
                if ($trimmed_host != $host) {
                    $changed = 1;
                    $host = $trimmed_host;
                }
            }

            if (! $changed) {
                break;
            }

            ### make sure we don't loop infinitely
            $loop_count++;
            if ($loop_count >= 10) {
               // logerror(ERROR_SEVERE, "csn300", "10 loops reached for getSitenameFromHost(): $host (was $orig_host)");
                break;
            }
        }

        ### Evgen 08/Jul/2015 Use suffix list to tidy hostname
        $suffix_to_host = array(
            ### BTH 5/Mar/15 we don't want zoocdn.com to appear so convert to zoopla.co.uk
            "zoocdn.com" => "zoopla.co.uk",
            ### BTH 5/Mar/15 convert eg Webbers.reapit.com to Reapit.com
            ".reapit.com" => "reapit.com",
            ### BTH 5/Mar/15 convert eg  Struttandparker.reapitcloud.com to Reapitcloud.com
            ".reapitcloud.com" => "reapitcloud.com",
            ### BTH 30/Jun/15 convert eg haweb.aspasia.net to aspasia.net
            ".aspasia.net" => "aspasia.net",
            ### BTH 3/Jul/15 convert eg eden.10ninety.co.uk to 10ninety.co.uk
            ".10ninety.co.uk" => "10ninety.co.uk",
            ### BTH 29/Jun/18 convert eg 45fe393200d2ba1a0900-a8f9aeb7b59a4e8c135ba789dd04ce2b.r95.cf1.rackcdn.com to rackcdn.com
            ".rackcdn.com" => "rackcdn.com",
            ### BTH 3/Jul/18 convert eg webv.svr2.realcube.net
            ".realcube.net" => "realcube.net",
            ### BTH 22/Feb/22 convert eg s3-eu-west-1.amazonaws.com
            ".amazonaws.com" => "amazonaws.com",
            ### BTH 22/Feb/22 convert eg chips.chestertons.com, kea.pmproagent.co.uk and zoom995c.agentpro53.co.uk
            ".chestertons.com" => "chestertons.com",
            ".pmproagent.co.uk" => "pmproagent.co.uk",
            ".agentpro53.co.uk" => "agentpro53.co.uk",
        );

        foreach ($suffix_to_host as $suffix => $tidy_host) {
            if ($this->homeCoFuncs->ends_with(strtolower($host), $suffix)) {
                $host = $tidy_host;
                break;
            }
        }

        return ucfirst($host);
    }

    ### BTH 14/Jan/10 make this function public so it can called from /search/agents/details.htm
    ### make static so we don't have to create the object
    public function getSitenameFromSitecode(string $sitecode) {
        $agreed_priorities_named = $this->homeCoFuncs->get_agreed_priorities_named();
        if ($agreed_priorities_named[$sitecode]) {
            return ucfirst($agreed_priorities_named[$sitecode]);
        }
        $robot_priorities_named = $this->homeCoFuncs->get_robot_priorities_named();
        if ($robot_priorities_named[$sitecode]) {
            return ucfirst($robot_priorities_named[$sitecode]);
        }
    }

    ### Piotr 19/Oct/09 - deduce sitecode from url
    private function getSitecodeFromUrl($url) {
        $bits = parse_url($url);
        if ($bits['host']) {
            $sites = $this->homeCoFuncs->get_agreed_priorities_named();
            array_push($sites, $this->homeCoFuncs->get_robot_priorities_named());
            foreach ($sites as $name=>$site) {
                $site = quotemeta($site);
                if (preg_match("/\b$site\$/i", $bits['host'])) {
                    return $name;
                }
            }
        }
        return '';
    }
}