# Pipedrive-API-PHP

## Example: List 10 Organizations
    
    <?php 
    $pipedrive_api = new Pipedrive_api('PIPEDRIVE_API_KEY');
    if($organizations = $pipedrive_api->request('organizations', array('start' => 0, 'limit' => 10))) {

        print_r($organizations->data); 

    } else {
        
        echo $pipedrive_api->errors(); 

    }


## Example: List Organization Data
    
    <?php 
    $pipedrive_api = new Pipedrive_api('PIPEDRIVE_API_KEY');
    $organization_id = 99; 
    if($organization = $pipedrive_api->request('organizations/'. $organization_id)) {

        print_r($organization->data); 

    } else {
        
        echo $pipedrive_api->errors(); 

    }


## Example: UPDATE Organization Data
    
    <?php 

    $updated_name = 'Widget Company ABC'; 

    $pipedrive_api = new Pipedrive_api('PIPEDRIVE_API_KEY');
    $organization_id = 99; 
    if($organization = $pipedrive_api->put()->request('organizations/'. $organization_id, array(), array('name' => $updated_name))) {

        print_r($organization->data); 

    } else {
        
        echo $pipedrive_api->errors(); 

    }


## Example: Delete an Organization
    
    <?php 

    $updated_name = 'Widget Company ABC'; 

    $pipedrive_api = new Pipedrive_api('PIPEDRIVE_API_KEY');
    $organization_id = 99; 
    if($organization = $pipedrive_api->delete()->request('organizations/'. $organization_id)) {

        print_r($organization->data); 

    } else {
        
        echo $pipedrive_api->errors(); 

    }

## Example: List People from Organization ID XX
    
    <?php 
    $pipedrive_api = new Pipedrive_api('PIPEDRIVE_API_KEY');
    $organization_id = 99; 
    $persons = $pipedrive_api->request('organizations/'. $organization_id .'/persons, array('start' => 0, 'limit' => 30));

    if($persons->success === TRUE) {

        print_r($persons->data); 

    } else {
        
        echo $pipedrive_api->errors(); 

    }