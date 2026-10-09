<?php
class Gallery extends Controller {
    public function index() {
        $contentModel = $this->model('Home_content_model');
        $contentModel->setPage('gallery');

        $data = [
            'title' => 'Gallery - Workiify',
            'f' => $contentModel->getFields(['gallery_banner_image', 'gallery_banner_eyebrow', 'gallery_banner_heading', 'gallery_banner_tagline']),
            'photos' => $contentModel->getItems('gallery_photos'),
        ];
        $this->view('pages/gallery', $data);
    }
}
