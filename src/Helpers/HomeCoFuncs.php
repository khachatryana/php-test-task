<?php

class HomeCoFuncs
{
    ## does $haystack end with $end?
    public function ends_with($haystack, $end)
    {
        $end_len = strlen($end);
        if (substr($haystack, -1 * $end_len) == $end) {
            return 1;
        }
        return 0;
    }

    ### BTH 4/Nov/13 this code is read by WebSiteParams.pm::get_robot_priorities_named_data()
### test any changes using perl t/WebSiteParams_get_cached_foreign_constant.t
### keep in synch with tools/HomeCo/primary_sitecodes/primarysitecodes_bias_web.pm
    public function get_robot_priorities_named()
    {
        ### BTH 12/Feb/13 don't use domain name = 'other' as it's showing in /search/results.htm?site=relocation_agent_ext
        ### BTH 21/Jan/15 load.pl uses the sitecode order defined below to choose house2.sitecode
        ### so rank *_direct higher than other robots + propertypal below propertypal_data
        ### V.I. 18/Nov/2016 Add aspc_direct.
        $robot_priorities_names = array(
            ### BTH 9/Mar/18 guildproperty_agentweb should be a very reliable link to the agent's website
            ### BTH 26/Apr/18 although, they sometimes they link to the website of group the agent is in eg townandcountrysouthern.co.uk
            'guildproperty_agentweb' => 'other',

            ### BTH 4/Jun/20 sequencehome_agentweb should be a reliable link to the agent's website
            'sequencehome_agentweb' => 'other',

            ### BTH 15/Feb/18 include proptyle here because it links directly the agents' websites
            'proptyle' => 'other',

            ### BTH 27/Jan/15 put _ext + _direct sitecodes over non-agreed portal sitecodes
            ### because they often link directly to the agent's websites
            ### BTH 26/Apr/18 although, they often link to the agent's IT provider's website
            'propertypal_direct' => 'other',
            'aspc_direct' => 'other',
            'zoopla_direct' => 'other',
            'ulster_ext' => 'other',
            'primelocation_ext' => 'other',
            'guildproperty_ext' => 'other',
            'vebra2_ext' => 'other',
            'relocation_agent_ext' => 'other',
            'zoopla_robot_ext' => 'other',
            'primelocation_robot_ext' => 'other',
            'onthemarket_ext' => 'other',
            'sspc_ext' => 'other',
            ### BTH 19/Jun/18 promote allagents_ext because issue2304 msg74657 suggests these URLs are mostly good
            'allagents_ext' => 'other',
            'onedome_ext' => 'other',

            ### BTH 27/Jan/15 general non-agreed portal sitecodes
            'homesonview' => 'homesonview.co.uk',
            'connells' => 'connells.co.uk',
            'halifax' => 'halifax.co.uk',
            'yourmove' => 'your-move.co.uk',
            'ulster' => 'ulsterpropertysales.co.uk',
            'espc' => 'espc.com',
            'tspc' => 'tspc.co.uk',
            'dgspc' => 'dgspc.co.uk',
            'hspc' => 'hspc.co.uk',
            'aspc' => 'aspc.co.uk',
            'bspc' => 'bspc.co.uk',
            'pspc_robot' => 'pspc.co.uk',
            'housescape' => 'housescape.co.uk',
            'expertagent' => 'expertagent.co.uk',
            'daft' => 'daft.ie',
            'propertynews' => 'propertynews.com',
            'sequencehome' => 'sequencehome.co.uk',
            'teamprop' => 'teamprop.co.uk',
            'thinkproperty' => 'thinkproperty.com',
            'vebra' => 'vebra.com',
            'vebra2' => 'vebra.com',
            'dezrez' => 'dezrez.com',
            ### BTH 16/Oct/17 homeflow will be on agent's site
            'homeflow' => 'other',
            'propertypigeon' => 'propertypigeon.co.uk',
            'nethouseprices' => 'nethouseprices.com',
            'sspc' => 'sspc.co.uk',

            ### BTH 24/Jun/19 when suppressing tracked clicks to OTM and ZPG move below all other usable sites

            ### BTH 30/Jun/19 if we want to hide referrer for clicks to ZPG, so they don't know we're sending clicks when they aren't bidding
            ### need to keep zoopla and primelocation_upload below zoopla_robot and primelocation_robot
            'zoopla_robot' => 'zoopla.co.uk',
            'primelocation_robot' => 'primelocation.com',
            # 'smartestates' => 'smartestates.com',
            # 'zoopla' => 'zoopla.co.uk',
            # 'primelocation_upload' => 'primelocation.com',

            ### BTH 30/Jun/19 if we want to hide referrer for clicks to onthemarket, so OTM don't know we're sending clicks when they aren't bidding
            ### need to keep onthemarket_upload below onthemarket
            ### NB: I wanted to automate this but adjusting the arrays after defining them might not work with the perl code that reads them
            'onthemarket' => 'onthemarket.com',
            # 'onthemarket_upload' => 'onthemarket.com',

            ### BTH 24/Jun/19 hide referrer for clicks to s1homes, so they don't know we're sending clicks when they aren't bidding
            ### need to keep s1homes_upload below s1homes
            's1homes' => 's1homes.com',
            's1homes_upload' => 's1homes.com',

            ### BTH 27/Jan/15 the hidden unless unique robot sitecodes are near last resort
            'allagents' => 'allagents.co.uk',
            'trinitymirror' => 'other',
            'relocation_agent' => 'relocation-agent-network.co.uk',
            'guildproperty' => 'guildproperty.co.uk',
            'onedome' => 'onedome.com',

            ### BTH 27/Jan/15 _data sitecodes are only used in preference to hidden sites (bottom)
            'propertypal_data' => 'other',
            'aspc_data' => 'other',

            ### BTH 29/Sep/15 demote nethouseprices_ext links because some are broken
            'nethouseprices_ext' => 'other',

            ### BTH 15/Aug/20 demote mouseprice as is asking users to register to view page
            'mouseprice' => 'mouseprice.com',

            ### BTH 27/Jan/15 really, really don't want to use (hidden sites)
            'propertypal' => 'propertypal.com',
        );
        return $robot_priorities_names;
    }

    ### return http://www.home.co.uk - possibly based on template
### replacement for WEBSITE + WEBSITE_URL
    public function get_url_base()
    {
        $host = WEBSITE;

        ### first check whether we have access to Property_Search.inc.php and thus get_template_code_from_host
        if (function_exists('get_template_code_from_host')) {
            $server_template_code = get_template_code_from_host();
            if ($server_template_code) {
                $host = $server_template_code . '.' . DOMAIN;
            }
        }

        ### JP 11/11/2015 protocol depends on whether server has switched to https
        ### BJD 6/Apr/2017 Use new get_website_scheme
        $protocol = $this->get_website_scheme($host);

        return $protocol . '://' . $host;
    }
### BJD 4/Apr/2017 Get the scheme (eg http/https) for a given website hostname
### (expected use is eg. get_website_scheme(ADMIN_WEBSITE),
### get_website_scheme(MAIN_WEBSITE)). Allows different websites to run with different
### schemes.
### XXX NOTE XXX This function is the main configuration of whether websites
### XXX are served by HTTPS or HTTP. New sites can either be added explicitly
### XXX here or (for instance on client sites where we may have a number of different
### XXX internal-LAN only hostnames) the default can be overridden in the
### XXX WebSiteLocal_*.php files with DEFAULT_WEBSITE_SCHEME
###
### NB: should be kept in sync with in logic in WebSiteParams.pm function
### of the same name (sub get_website_scheme)
### BJD 10/Apr/2017 Moved to WebSiteParams.php to avoid circular dependency
### between HomeCoFuncs.php and WebSiteParams.php
### BJD 10/Apr/2017 Remove call to get_template_code as it is not available in
### WebSiteParams.php (and this check is not strictly needed)
    public function get_website_scheme($website)
    {
        # Define each HomeCo main website individually so it's easy
        # to configure them. Note these are defined using the _LIVE
        # constants to only match the live site website names.
        ### BJD 17/Apr/2018 Switch live main site to https
        if ($website === MAIN_WEBSITE_LIVE) {
            return 'https';
        } ### BJD 31/Aug/2017 Switch live mobile site to https
        elseif ($website === MOBILE_WEBSITE_LIVE) {
            return 'https';
        } ### BJD 11/Oct/2017 Switch live admin site to https
        elseif ($website === ADMIN_WEBSITE_LIVE) {
            return 'https';
        } elseif ($website === WEBSERVICES_WEBSITE_LIVE) {
            return 'http';
        } else {
            # Return the default
            # This will cover any website name not yet
            # checked (note this includes template sites
            # (eg. see HomeCoFuncs.php get_template_code)
            # so if one of those sites needs to use a
            # non-default value add it to the above list
            # as a custom website.
            return DEFAULT_WEBSITE_SCHEME;
        }
    }

### JP 04/10/2018 returns whether Mapbox should be used as maps provider
    public function are_we_using_mapbox()
    {
        return 1;
    }

### return $agreed_priorities and $robot_priorities
### for use in Property_Search.inc.php and agent/details.htm
### COPY FROM: Agents/dbadmin/primarysitecodes.pl
### -- but include sites that don't have siteagents but which aren't authority sites eg estatesit + smartestates
### 'primelocation' low because ask the user to register
### if not listed in either then assume it's the authority site
    public function get_agreed_priorities()
    {
        $agreed_priorities_names = $this->get_agreed_priorities_named();
        return array_keys($agreed_priorities_names);
    }

    public function get_robot_priorities()
    {
        $robot_priorities_names = $this->get_robot_priorities_named();
        return array_keys($robot_priorities_names);
    }
### BTH 4/Nov/13 this code is read by WebSiteParams.pm::get_agreed_priorities_named_data()
### test any changes using perl t/WebSiteParams_get_cached_foreign_constant.t
### NB: also keep in synch with tools/HomeCo/primary_sitecodes/primarysitecodes_bias_web.pm + $g_cpc_bids above
### BTH 15/Jul/16 onthemarket_upload to bottom of list to maximise the drop-off when their bid stops
    public function get_agreed_priorities_named()
    {
        $agreed_priorities_names = array(
            'propertylogic' => 'other',
            'smartnewhomes' => 'smartnewhomes.com',
            'propertywide' => 'propertywide.co.uk',
            'knightfrank' => 'knightfrank.com',
            'winkworth' => 'winkworth.co.uk',
            'pspc' => 'pspc.co.uk',
            'easoft' => 'estate-software.co.uk',
            'acquaintcrm' => 'acquaintcrm.co.uk',
            'estatesit' => '24-7estates.com',
            'inea' => 'inea.co.uk',
            'foxtons' => 'foxtons.co.uk',
            'propertyheads' => 'propertyheads.com',
            'commercialpeople' => 'residentialpeople.com',
            'houser' => 'houser.co.uk',
            'countrywide' => 'propertywide.co.uk',
            'countrywide_upload' => 'propertywide.co.uk',
            'countrywide_reapit' => 'propertywide.co.uk',
            'gspc_upload' => 'gspc.co.uk',
            'gspc' => 'gspc.co.uk',
            'gspc2' => 'gspc.co.uk',
            'houseladder' => 'houseladder.co.uk',
            'pring' => 'pring.co.uk',
            'alexneil' => 'alexneil.com',
            'viewpoint_upload' => 'viewpoint.net.uk',  ### NB: viewpoint aren't including URLs at the moment
            'zoomf' => 'zoomf.co.uk',
            'radarhomes' => 'radarhomes.co.uk',
            'moveto' => 'moveto.co.uk',
            'citylets' => 'citylets.co.uk',
            'london2let' => 'london2let.com',
            'propertyfinder2' => 'propertyfinder.com',
            'propertyadd' => 'propertyadd.com',
            'propertiesdirect' => 'propertiesdirect.co.uk',
            'look4aproperty' => 'look4aproperty.com',
            'surf4aproperty' => 'surfaproperty.com',
            'propertyplatform' => 'propertyplatform.co.uk',
            'propertybank' => 'propertybank.com',
            'goforproperty' => 'goforproperty.com',
            'propertylive' => 'propertylive.co.uk',
            'countrylife' => 'countrylife.co.uk',
            'tlhc' => 'thehouseshop.com',
            'movewithus_globrix' => 'movewithus.co.uk',  ### movewithus_globrix should have agent domain
            'prptoday' => 'propertytoday.co.uk',
            'fish4' => 'fish4.co.uk',
            'homesandp' => 'homesandproperty.co.uk',
            'independent' => 'independent.co.uk',
            'bristolhfs' => 'bristolhousesforsale.co.uk',
            'findaproperty_upload' => 'findaproperty.com',
            'findaproperty' => 'findaproperty.com',
            'movewithus' => 'movewithus.co.uk',
            'propertyindex' => 'propertyindex.com',
            'oodle' => 'oodle.com',    ### hopefully will override oodle.com on results pages
            'hotproperty' => 'hotproperty.co.uk',
            'winkworth_robot' => 'winkworth.co.uk',
            'winkworth2' => 'winkworth.co.uk',
            ### push down needaproperty unbid properties
            'needaproperty2' => 'needaproperty.com',
            'email4property' => 'email4property.co.uk',
            'homes24_upload' => 'homes24.co.uk',
            'homes24' => 'homes24.co.uk',
            'myhomesunlimited' => 'myhomesunlimited.com',
            'homein' => 'homein.com',
            'propertysquirrel' => 'propertysquirrel.com',
            'dssmove' => 'dssmove.co.uk',
            'onedome_upload' => 'onedome.com',
            'expertagent_upload' => 'expertagent.co.uk',
            'whathouse' => 'whathouse.co.uk',
            'nethouseprices_upload' => 'nethouseprices.com',
            'rightmove_upload' => 'rightmove.co.uk',
            # 's1homes_upload' => 's1homes.com',
            'onthemarket_upload' => 'onthemarket.com',
            'smartestates' => 'smartestates.com',
            'zoopla' => 'zoopla.co.uk',
            'primelocation_upload' => 'primelocation.com',
            'sourced' => 'sourced.co',
            'spicerhaart' => 'spicerhaart.co.uk',
            'propertyxyz' => 'property.xyz',
            'realtywwinfo' => 'realtyww.info',
        );
        return $agreed_priorities_names;
    }
}