<?php
class Error404 extends Controller {
    public function index() {
        // Send a 404 response code
        http_response_code(404);
        $data = [
            'title' => '404 Page Not Found - Workiify'
        ];
        $this->view('pages/404', $data);
    }
}
