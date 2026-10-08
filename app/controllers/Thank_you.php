<?php
class Thank_you extends Controller {
    public function index() {
        $data = [
            'title' => 'Thank You - Workiify'
        ];
        $this->view('pages/thank_you', $data);
    }
}
