<?php
defined('_ICMS') or die;

/**
 * Update Script
 * Date: 7/3/2016
 */
function start()
{
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
    if ($res === TRUE) {
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
    rename("tmp/CMS-master/core" , "core");
    rename("tmp/CMS-master/vendor" , "vendor");

    rmdir('tmp');

}