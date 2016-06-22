<?php
/*************************************************************

Simple site crawler to create a search engine XML Sitemap.
Version 2.0-test1
Free to use, without any warranty.
Written by Elmar Hanlhofer https://www.plop.at 01/Feb/2012.

ChangeLog:
----------
Version 2.0-test1 2016/05/28 by Elmar Hanlhofer

 * Most program parts rewritten

Version 1.0 2015/10/14 by Elmar Hanlhofer

 * CLI / Website mode added
 * Multiple extension support added
 * Support for quoted URLs with spaces added
 * Skip mailto links
 * Converting special chars in the URLs for the XML file
 * Added user agent
 * Minor code updates

Version 0.2 2013/01/16

 * curl support - by Emanuel Ulses
 * write url, then scan url - by Elmar Hanlhofer

Version 0.1 2012/02/01 by Elmar Hanlhofer

 * Initital release

 *************************************************************/
class Sitemap {

    // Set the output file name.
    private $output_file;
    // Set the start URL. Here is https used, use http:// for non SSL websites.
    private $site;

    // Define here the URLs to skip. All URLs that start with the defined URL 
    // will be skipped too.
    // Example: "https://www.plop.at/print" will also skip
    //   https://www.plop.at/print/bootmanager.html

    // Scan frequency
    private $frequency;
    // Page priority
    private $priority;
    private $pf;
    private $scanned;
    private $site_scheme;
    private $site_host;
    private $agent;

    /***********************************************/
    /* Init end                                    */
    /***********************************************/
    function __construct($site) {
        $this->output_file = "sitemap.xml";
        $this->site = $site;
        $this->skip_url = array (
            "http://".$site."/templates/default/css/style.css",
            "http://".$site."/templates/admin/css/pnotify.custom.min.css",
            "http://fonts.googleapis.com/css?family=Open+Sans"
        );
        $this->frequency = "daily";
        $this->priority = "0.5";
        $this->generate();
    }

    function GetURL ($url)
    {

        $ch = curl_init ($url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_USERAGENT, $this->agent);

        $data = curl_exec($ch);

        curl_close($ch);

        return $data;
    }

    function GetQuotedUrl ($str)
    {
        if ($str[0] != '"') return $str; // Only process a string
        // starting with double quote
        $ret = "";

        $len = strlen ($str);
        for ($i = 1; $i < $len; $i++) // Start with 1 to skip first quote
        {
            if ($str[$i] == '"') break; // End quote reached
            $ret .= $str[$i];
        }

        return $ret;
    }


    function GetHREFValue ($anchor)
    {

        $split1  = explode ("href=", $anchor);
        $split2 = explode (">", $split1[1]);
        $href_string = $split2[0];


        if ($href_string[0] == '"')
        {
            $url = $this->GetQuotedUrl ($href_string);
        }
        else
        {
            $spaces_split = explode (" ", $href_string);
            $url          = $spaces_split[0];
        }
        return $url;
    }

    function GetEffectiveURL ($url)
    {
        // Create a curl handle
        $ch = curl_init ($url);

        // Send HTTP request and follow redirections
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_USERAGENT, $this->agent);
        curl_exec($ch);

        // Get the last effective URL
        $effective_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        // ie. "http://example.com/show_location.php?loc=M%C3%BCnchen"

        // Decode the URL, uncoment it an use the variable if needed
        // $effective_url_decoded = curl_unescape($ch, $effective_url);
        // "http://example.com/show_location.php?loc=München"

        // Close the handle
        curl_close($ch);

        return $effective_url;
    }


    function ValidateURL ($url_base, $url)
    {

        $parsed_url = parse_url ($url);

        $scheme = $parsed_url["scheme"];

        // Skip URL if different scheme or not relative URL (skips also mailto)
        if (($scheme != $this->site_scheme) && ($scheme != "")) return false;

        $host = $parsed_url["host"];

        // Skip URL if different host
        if (($host != $this->site_host) && ($host != "")) return false;


        if ($host == "")    // Handle URLs without host value
        {
            if ($url[0] == '#') // Handle page anchor
            {
                echo "Skip page anchor: $url" . "<br>";
                return false;
            }

            if ($url[0] == '/') // Handle absolute URL
            {
                $url = $this->site_scheme . "://" . $this->site_host . $url;
            }
            else // Handle relative URL
            {

                $path = parse_url ($url_base, PHP_URL_PATH);

                if (substr ($path, -1) == '/') // URL is a directory
                {
                    // Construct full URL
                    $url = $this->site_scheme . "://" . $this->site_host . $path . $url;
                }
                else // URL is a file
                {
                    $dirname = dirname ($path);

                    // Add slashes if needed
                    if ($dirname[0] != '/')
                    {
                        $dirname = "/$dirname";
                    }

                    if (substr ($dirname, -1) != '/')
                    {
                        $dirname = "$dirname/";
                    }

                    // Construct full URL
                    $url = $this->site_scheme . "://" . $this->site_host . $dirname . $url;
                }
            }
        }

        // Get effective URL, follow redirected URL
        $url = $this->GetEffectiveURL ($url);

        // Don't scan when already scanned
        if (in_array ($url, $this->scanned)) return false;

        return $url;
    }

// Skip URLs from the $skip_url array
    function SkipURL ($url)
    {

        if (isset ($this->skip_url))
        {
            foreach ($this->skip_url as $v)
            {
                if (substr ($url, 0, strlen ($v)) == $v) return true; // Skip this URL
            }
        }

        return false;
    }


    function Scan ($url)
    {

        array_push ($this->scanned, $url);

        if ($this->SkipURL ($url))
        {
            echo "Skip $url" . "<br>";
            return false;
        }

        // Remove unneeded slashes
        if (substr ($url, -2) == "//")
        {
            $url = substr ($url, 0, -2);
        }
        if (substr ($url, -1) == "/")
        {
            $url = substr ($url, 0, -1);
        }


        echo "Scan $url" . "<br>";

        $headers = @get_headers ($url, 1);

        // Handle pages not found
        if (strpos ($headers[0], "404") !== false)
        {
            echo "Not found: $url". "<br>";
            return false;
        }

        // Handle redirected pages
        if (strpos ($headers[0], "301") !== false)
        {
            $url = $headers["Location"];
            echo "Redirected to: $url". "<br>";
            array_push ($this->scanned, $url);
        }

        // Get content type
        if (is_array ($headers["Content-Type"]))
        {
            $content = explode (";", $headers["Content-Type"][0]);
        }
        else
        {
            $content = explode (";", $headers["Content-Type"]);
        }

        // Check content type for website
        if ($content[0] != "text/html")
        {
            echo "Info: $url is not a website: $content[0]" . "<br>";
            return false;
        }


        $html = $this->GetURL ($url);
        $html = trim ($html);
        if ($html == "") return true;  // Return on empty page

        $html = str_replace ("\r", " ", $html);        // Remove newlines
        $html = str_replace ("\n", " ", $html);        // Remove newlines
        $html = str_replace ("<A ", "<a ", $html);     // <A to lowercase
        $html = substr ($html, strpos ("<a ", $html)); // Start from first anchor

        $a1   = explode ("<a", $html);

        foreach ($a1 as $next_url)
        {
            $next_url = trim ($next_url);

            // Skip first array entry
            if ($next_url == "") continue;

            // Get the attribute value from href
            $next_url = $this->GetHREFValue ($next_url);

            // Do all skip checks and construct full URL
            $next_url = $this->ValidateURL ($url, $next_url);

            // Skip if url is not valid
            if ($next_url == false) continue;

            if ($this->Scan ($next_url))
            {
                // Add url to sitemap
                fwrite ($this->pf, "  <url>\n" .
                    "    <loc>" . htmlentities ($next_url) ."</loc>\n" .
                    "    <changefreq>" . $this->frequency . "</changefreq>\n" .
                    "    <priority>" . $this->priority . "</priority>\n" .
                    "  </url>\n");
            }
        }

        return true;
    }
    /**
     * Author: https://github.com/pawelantczak
     * Source: https://github.com/pawelantczak/php-sitemap-generator/blob/master/SitemapGenerator.php
     * Will inform search engines about newly created sitemaps.
     * Google, Ask, Bing and Yahoo will be noticed.
     * If You don't pass yahooAppId, Yahoo still will be informed,
     * but this method can be used once per day. If You will do this often,
     * message that limit was exceeded  will be returned from Yahoo.
     * @param string $yahooAppId Your site Yahoo appid.
     * @return array of messages and http codes from each search engine
     * @access public
     */
    public function submitSitemap($yahooAppId = null) {
        if (!isset($this->sitemaps))
            throw new BadMethodCallException("To submit sitemap, call createSitemap function first.");
        if (!extension_loaded('curl'))
            throw new BadMethodCallException("cURL library is needed to do submission.");
        $searchEngines = $this->searchEngines;
        $searchEngines[0] = isset($yahooAppId) ? str_replace("USERID", $yahooAppId, $searchEngines[0][0]) : $searchEngines[0][1];
        $result = array();
        for($i=0;$i<sizeof($searchEngines);$i++) {
            $submitSite = curl_init($searchEngines[$i].htmlspecialchars($this->sitemapFullURL,ENT_QUOTES,'UTF-8'));
            curl_setopt($submitSite, CURLOPT_RETURNTRANSFER, true);
            $responseContent = curl_exec($submitSite);
            $response = curl_getinfo($submitSite);
            $submitSiteShort = array_reverse(explode(".",parse_url($searchEngines[$i], PHP_URL_HOST)));
            $result[] = array("site"=>$submitSiteShort[1].".".$submitSiteShort[0],
                "fullsite"=>$searchEngines[$i].htmlspecialchars($this->sitemapFullURL, ENT_QUOTES,'UTF-8'),
                "http_code"=>$response['http_code'],
                "message"=>str_replace("\n", " ", strip_tags($responseContent)));
        }
        return $result;
    }

    function generate() {
        $version = "2.0-test1";
        $this->agent = "Mozilla/5.0";
        //define (AGENT, "Mozilla/5.0 (compatible; Plop PHP XML Sitemap Generator/" . VERSION . ")");
        //define ($this->site_SCHEME, parse_url ($this->site, PHP_URL_SCHEME));
        //define ($this->site_HOST  , parse_url ($this->site, PHP_URL_HOST));
        if (empty($_SERVER['HTTPS'])) {
            $this->site_scheme = "http";
        } else {
            $this->site_scheme = "https";
        }
        $this->site_host = $this->site;

        $this->pf = fopen ($this->output_file, "w");
        if (!$this->pf)
        {
            echo "Cannot create " . $this->output_file . "!" . "<br>";
            return;
        }

        fwrite ($this->pf, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" .
            "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n" .
            "  <url>\n" .
            "    <loc>" . $this->site_scheme . "://" . $this->site_host . "/</loc>\n" .
            "    <changefreq>" . $this->frequency . "</changefreq>\n" .
            "  </url>\n");

        $this->scanned = array();
        $this->Scan ($this->GetEffectiveURL ($this->site));

        fwrite ($this->pf, "</urlset>\n");
        fclose ($this->pf);

        echo "Done." . "<br>";
        echo $this->output_file . " created." . "<br>";
        echo "<button onclick=\"history.go(-1);\">Back </button>";
        die();
    }

}
