<?php
    /**
     * PipeDrive API Example
     *
     * @website https://www.inboxdesign.co.nz/
     * @author Sheldon Lendrum
     **/

    // error_reporting(0); ini_set('display_errors', 0);
    error_reporting(-1); ini_set('display_errors', 1);

    // define your Pipedrive API key
    // https://developers.pipedrive.com/v1
    define('PIPEDRIVE_API_KEY', '1234567890abcABCdefDEF');

    // include pipedrive API class
    require_once('pipedrive_api.php');



    // include helper functions
    // only for the display of data in this example.
    require_once('functions.php');



    // init class
    $pipedrive_api = new Pipedrive_api();
    $pipedrive_api->setApiToken(PIPEDRIVE_API_KEY);


    // set some defaults for pagination.
    $start = 0;
    $limit = 10;

    if(isset($_GET['start'])) $start = $_GET['start'];
    if(isset($_GET['limit'])) $limit = $_GET['limit'];



    if($organizations = $pipedrive_api->request('organizations', array('start' => $start, 'limit' => $limit))) {

        echo '<h1>'. count($organizations->data) .' Results</h1>';

        if($organizations->additional_data) {
            echo '<p>Showing: '. ($organizations->additional_data->pagination->start+1) .' to '. ($organizations->additional_data->pagination->start + $organizations->additional_data->pagination->limit) .'. ';
            if($organizations->additional_data->pagination->more_items_in_collection) {
                echo ' <a href="?s='. $organizations->additional_data->pagination->next_start .'">Next &gt;&gt;</a>';
            }
            echo '</p>';
        }

        foreach($organizations->data as $record) {

            echo '<a href="#c'. $record->id.'">'. $record->name .'</a>, ';

        }

        foreach($organizations->data as $record) {

            echo '<h1 id="c'. $record->id .'">'. $record->name .' <a href="#">^</a></h1>';
            echo build_table($record);

            if($persons = $pipedrive_api->request('organizations/'. $record->id .'/persons')) {

                echo '<h3>'. count($persons->data) .' Persons</h3>';

                foreach($persons->data as $person) {

                    echo '<h4>'. $person->name .' </h4>';
                    echo build_table($person);

                }

            } else {

                echo $pipedrive_api->errors();

            }

            echo '<hr>';

        }

    } else {

        echo $pipedrive_api->errors();

    }


