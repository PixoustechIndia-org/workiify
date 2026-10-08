<?php
/*
 * Base Controller
 * Loads the models and views
 */
class Controller {
    // Load model
    public function model($model){
        // Require model file
        require_once '../app/models/' . $model . '.php';

        // Instantiate model
        return new $model();
    }

    // Load view
    public function view($view, $data = []){
        // Sanitize data before sending to view to prevent XSS where appropriate
        // Data should ideally be escaped in the view itself for context-specific output.
        
        // Check for view file
        if(file_exists('../app/views/' . $view . '.php')){
            require_once '../app/views/' . $view . '.php';
        } else {
            // View does not exist
            require_once '../app/views/pages/404.php';
        }
    }
}
