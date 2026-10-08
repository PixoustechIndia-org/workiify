<?php
class About_us extends Controller {
    public function index() {
        $data = [
            'title' => 'About Us - Workiify'
        ];
        $this->view('pages/about_us', $data);
    }
}
