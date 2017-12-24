<?php
defined('_ICMS') or die;

/**
 * Update Script
 * Date: 7/3/2016
 */
function start()
{
    $latest = json_decode(file_get_contents("https://raw.githubusercontent.com/Nixhatter/CMS/master/version.json"), true);
    $latest_version = "https://github.com/Nixhatter/CMS/archive/master.zip";
    $icms_zip = "icms.zip";

    if (!is_dir('tmp')) {
        mkdir('tmp');
    }

    /*
     * Download the newest version from github
     */
    file_put_contents("tmp/" . $icms_zip, fopen($latest_version, 'r'));

    /*
     * Unzip
     */
    $zip = new ZipArchive;
    $res = $zip->open("tmp/" . $icms_zip);
    if ($res === true) {
        $zip->extractTo('tmp');
        $zip->close();
    } else {
        echo 'Could not extract file, error: ' . $res;
    }

    /*
     * Delete the old folders
     */
    rmdir('core');
    rmdir('vendor');

    /*
     * Replace them with the downloaded ones
     */
    rename("tmp/CMS-master/core", "core");
    rename("tmp/CMS-master/vendor", "vendor");

    /*
     * Update the Database
     */
    $dbhost = $settings->production->database->host;
    $dbport = $settings->production->database->port;
    $dbname = $settings->production->database->name;
    $dbuser = $settings->production->database->user;
    $dbpass = $settings->production->database->password;


    $files = scandir('database/');
    foreach ($files as $file) {
        if (substr($file, 0, 5) === $latest) {
            $conn = new PDO("mysql:host=".$dbhost.";port=".$dbport.";dbname=".$dbname.";", $dbuser, $dbpass);

            $queries = getQueriesFromSQLFile($file);

            foreach ($queries as $query) {
                try {
                    $conn->exec($query);
                } catch (Exception $e) {
                    exit($e->getMessage() . "<br /> <p>The" . $query . " </p>");
                }
            }
        }
    }
    rmdir('tmp');
}

/* SQL Restore Function */
function getQueriesFromSQLFile($sqlfile)
{
    if (is_readable($sqlfile) === false) {
        throw new Exception($sqlfile . 'does not exist or is not readable.');
    }
    # read file into array
    $file = file($sqlfile);

    # import file line by line
    # and filter (remove) those lines, beginning with an sql comment token
    $file = array_filter(
        $file,
        create_function(
            '$line',
            'return strpos(ltrim($line), "--") !== 0;'
        )
    );

    # and filter (remove) those lines, beginning with an sql notes token
    $file = array_filter(
        $file,
        create_function(
            '$line',
            'return strpos(ltrim($line), "/*") !== 0;'
        )
    );

    # this is a whitelist of SQL commands, which are allowed to follow a semicolon
    $keywords = array(
        'ALTER', 'CREATE', 'DELETE', 'DROP', 'INSERT',
        'REPLACE', 'SELECT', 'SET', 'TRUNCATE', 'UPDATE', 'USE'
    );

    # create the regular expression for matching the whitelisted keywords
    $regexp = sprintf('/\s*;\s*(?=(%s)\b)/s', implode('|', $keywords));

    # split there
    $splitter = preg_split($regexp, implode("\r\n", $file));

    # remove trailing semicolon or whitespaces
    $splitter = array_map(
        create_function(
        '$line',
        'return preg_replace("/[\s;]*$/", "", $line);'
    ),
        $splitter
    );

    # remove empty lines

    return array_filter($splitter, create_function('$line', 'return !empty($line);'));
}
