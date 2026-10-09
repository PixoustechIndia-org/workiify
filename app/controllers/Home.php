<?php
class Home extends Controller {
    public function index() {
        $contentModel = $this->model('Home_content_model');
        $testimonialModel = $this->model('Testimonial');

        $fieldKeys = [
            'services_heading', 'services_subtitle', 'services_cta_text', 'services_cta_link',
            'amenities_heading', 'amenities_subtitle',
            'welcome_heading', 'welcome_text', 'welcome_image', 'welcome_btn_text', 'welcome_btn_link',
            'whychoose_heading', 'whychoose_image',
            'audience_heading', 'audience_subtitle',
            'testimonials_heading', 'google_rating',
            'gallery_heading', 'gallery_subtitle', 'gallery_cta_text', 'gallery_cta_link',
            'contact_badge', 'contact_heading_line1', 'contact_heading_highlight', 'contact_subtitle',
            'contact_address', 'contact_phone', 'contact_phone_link', 'contact_email',
        ];

        $data = [
            'title' => 'Home - Workiify',
            'description' => 'Welcome to Workiify',
            'f' => $contentModel->getFields($fieldKeys),
            'hero' => $contentModel->getItems('hero'),
            'counters' => $contentModel->getItems('counters'),
            'services' => $contentModel->getItems('services'),
            'amenities' => $contentModel->getItems('amenities'),
            'whyChoosePoints' => $contentModel->getItems('why_choose_points'),
            'audience' => $contentModel->getItems('audience'),
            'testimonials' => $testimonialModel->getHomeTestimonials(),
            'gallery' => $contentModel->getItems('gallery'),
        ];

        $this->view('pages/home', $data);
    }
}
