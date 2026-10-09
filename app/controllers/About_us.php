<?php
class About_us extends Controller {
    public function index() {
        $contentModel = $this->model('Home_content_model');
        $testimonialModel = $this->model('Testimonial');
        $contentModel->setPage('about');

        $fieldKeys = [
            'about_banner_image', 'about_banner_heading', 'about_banner_tagline',
            'about_story_eyebrow', 'about_story_heading', 'about_story_text', 'about_story_image',
            'purpose_mission_heading', 'purpose_mission_text', 'purpose_vision_heading', 'purpose_vision_text',
            'workspace_eyebrow', 'workspace_heading', 'workspace_subtitle',
            'whychoose_heading',
            'location_heading', 'location_text', 'location_image',
            'community_heading', 'community_subtitle', 'community_quote', 'community_note',
            'about_testimonials_heading', 'about_google_rating',
            'client_logos_heading',
        ];

        $data = [
            'title' => 'About Us - Workiify',
            'f' => $contentModel->getFields($fieldKeys),
            'workspaceTiles' => $contentModel->getItems('workspace_tiles'),
            'whyChooseCards' => $contentModel->getItems('why_choose_cards'),
            'whyChooseIcons' => $contentModel->getItems('why_choose_icons'),
            'communityPhotos' => $contentModel->getItems('community_photos'),
            'testimonials' => $testimonialModel->getHomeTestimonials(),
            'clientLogos' => $contentModel->getItems('client_logos'),
        ];

        $this->view('pages/about_us', $data);
    }
}
