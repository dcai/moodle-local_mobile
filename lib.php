<?php
/**
 * @package     local_mobile
 * @copyright   Dongsheng Cai {@link http://dongsheng.org}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Serves language files
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @return bool false if file not found, does not return if found - justsend the file
 */
function local_mobile_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload) {
    $langs = array('en' => 'en',
                   'fr' => 'fr',
                   'zh_cn' => 'zh-Hans',
                   'zh_tw' => 'zh-Hant',
                   'ja' => 'ja',
                    );
    local_mobile_generate_languages($langs);
    die;
}

/**
 * Lists all browsable file areas
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return array
 */
function local_mobile_get_file_areas($course, $cm, $context) {
    $areas = array('download');
    return $areas;
}

/**
 * Generate language packs
 *
 * @param array $langs
 */
function local_mobile_generate_languages($langs = array()) {
    global $CFG;
    $zipper = get_file_packer('application/zip');
    $fs = get_file_storage();
    $rootpath = $CFG->dataroot . '/local_mobile/lang';
    mkdir($rootpath, $CFG->directorypermissions, true);
    // $key is moodle language short name
    // $lang is xcode language short name
    foreach ($langs as key => $lang) {
        $langpath = $rootpath . '/' . $lang . '.lproj';
        mkdir($langpath, $CFG->directorypermissions, true);
        $text = local_mobile_get_language_text($key);
        file_put_contents($langpath . '/'. 'Localizable.strings', $text);
    }
    $filelist = array(
        '/' => $rootpath
    );

    $zipfile = $CFG->dataroot . '/local_mobile/lang.zip';

    if (file_exists($zipfile)) {
        unlink($zipfile);
    }
    $zipper->archive_to_pathname($filelist, $zipfile);
    local_mobile_cleanup_files();
    send_file($zipfile, 'ios_lang.zip');
}

/**
 * Clean up language pack files
 *
 * @param string $dir
 */
function local_mobile_empty_tree($dir) { 
    $files = glob($dir . '*', GLOB_MARK); 
    foreach( $files as $file ){ 
        if( substr( $file, -1 ) == '/' ) { 
            local_mobile_empty_tree( $file ); 
        } else { 
            unlink( $file ); 
        }
    } 
    rmdir($dir); 
} 

/**
 * Clean up files
 */
function local_mobile_cleanup_files() {
    global $CFG;
    $rootpath = $CFG->dataroot . '/local_mobile/lang/';
    local_mobile_empty_tree($rootpath);
}

/**
 * Generate language file content
 * @param string $lang
 * @return string
 */
function local_mobile_get_language_text($lang) {

    $text = '';
    $manager = get_string_manager();
    $bom = chr(255) . chr(254);

    $results = $manager->load_component_strings('local_mobile', $lang);

    foreach ($results as $key=>$value) {
        $string = "\"" . $key . "\"" . " = \"" . $value . "\";\n";
        $text .= iconv('UTF-8', 'UTF-16', $string);
    }

    return $text;
}
