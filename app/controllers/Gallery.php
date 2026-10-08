<?php
class Gallery extends Controller {
    public function index() {
        $data = [
            'title' => 'Gallery - Workiify'
        ];
        $this->view('pages/gallery', $data);
    }
}
