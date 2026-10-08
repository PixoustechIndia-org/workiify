<?php
class Home extends Controller {
    public function index() {
        $data = [
            'title' => 'Home - Workiify',
            'description' => 'Welcome to Workiify'
        ];
        $this->view('pages/home', $data);
    }
}
