<?php
class Contact_us extends Controller {
    public function index() {
        $data = [
            'title' => 'Contact Us - Workiify'
        ];
        $this->view('pages/contact_us', $data);
    }
}
