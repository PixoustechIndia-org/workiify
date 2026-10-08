<?php
class Privacy_policy extends Controller {
    public function index() {
        $data = [
            'title' => 'Privacy Policy - Workiify'
        ];
        $this->view('pages/privacy_policy', $data);
    }
}
