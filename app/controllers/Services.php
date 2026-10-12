<?php
/*
 * Services
 *   /services            -> the services listing
 *   /services/{slug}     -> one service, looked up in the services table (managed in Admin > Services)
 *
 * Every service renders through the one shared template, pages/service_page, from its content in
 * the admin (Service_schema describes the sections).
 */
class Services extends Controller {
    private $serviceModel;

    public function __construct() {
        $this->serviceModel = $this->model('Service_model');
    }

    public function index($slug = null, ...$extra) {
        if ($slug === null || $slug === '') {
            $this->view('pages/services', [
                'title' => 'Services - Workiify',
                'groups' => $this->serviceModel->listingGrouped(),
            ]);
            return;
        }
        if (!empty($extra)) {
            $this->notFound();
            return;
        }
        $this->renderService((string)$slug);
    }

    private function renderService($slug) {
        $isAdmin = $this->isAdmin();
        $service = $this->serviceModel->findBySlug($slug, $isAdmin);

        if (!$service) {
            // A service whose URL was changed in the admin keeps working via a permanent redirect.
            $moved = $this->serviceModel->findByOldSlug($slug);
            if ($moved) {
                header('Location: ' . URLROOT . '/services/' . $moved['slug'], true, 301);
                exit;
            }
            $this->notFound();
            return;
        }

        $data = [
            'title' => $service['meta_title'] !== '' ? $service['meta_title'] : $service['title'] . ' - Workiify',
            'description' => $service['meta_description'] !== '' ? $service['meta_description'] : $service['card_summary'],
            'service' => $service,
            'content' => $this->serviceModel->pageContent($service['id']),
            'related' => $this->serviceModel->related($service['id']),
            'isDraft' => $service['status'] !== 'published',
            'hideTourBand' => true,
        ];
        $this->view('pages/service_page', $data);
    }

    private function notFound() {
        http_response_code(404);
        $this->view('pages/404', ['title' => '404 Page Not Found - Workiify']);
    }

    // Drafts are visible to a logged-in admin (as a preview). Only resumes an existing
    // session; never starts one for ordinary visitors.
    private function isAdmin() {
        if (session_status() === PHP_SESSION_NONE && !empty($_COOKIE[session_name()])) {
            session_start();
            $ok = !empty($_SESSION['admin_logged_in']);
            session_write_close();
            return $ok;
        }
        return !empty($_SESSION['admin_logged_in']);
    }
}
