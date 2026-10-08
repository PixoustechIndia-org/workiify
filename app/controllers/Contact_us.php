<?php
class Contact_us extends Controller {
    public function index() {
        $data = [
            'title' => 'Contact Us - Workiify',
            'hideTourBand' => true
        ];
        $this->view('pages/contact_us', $data);
    }
}
