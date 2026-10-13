<?php
class Testimonials extends Controller {
    public function __construct() {
        $this->testimonialModel = $this->model('Testimonial');
    }

    public function index() {
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Normalise POST data: plain text only (HTML tags removed). Output is escaped where it is
            // displayed, so quotes etc. are kept as typed. (FILTER_SANITIZE_STRING is deprecated in PHP 8.1+.)
            $_POST = array_map(function ($v) { return is_string($v) ? trim(strip_tags($v)) : ''; }, $_POST);
            
            $data = [
                'full_name' => trim($_POST['full_name'] ?? ''),
                'company' => trim($_POST['company'] ?? ''),
                'designation' => trim($_POST['designation'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'rating' => trim($_POST['rating'] ?? ''),
                'feedback' => trim($_POST['feedback'] ?? ''),
                'consent' => isset($_POST['consent']) ? 1 : 0,
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'photo' => null,
                'full_name_err' => '',
                'email_err' => '',
                'rating_err' => '',
                'feedback_err' => '',
                'consent_err' => '',
                'photo_err' => '',
                'general_err' => '',
                'success_msg' => ''
            ];

            // Validate
            if (empty($data['full_name'])) {
                $data['full_name_err'] = 'Please enter your full name';
            } elseif (strlen($data['full_name']) > 100) {
                $data['full_name_err'] = 'Name must be less than 100 characters';
            }

            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter your email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email format';
            }

            if (empty($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
                $data['rating_err'] = 'Please provide a rating between 1 and 5';
            }

            if (empty($data['feedback'])) {
                $data['feedback_err'] = 'Please enter your feedback';
            } elseif (strlen($data['feedback']) < 20 || strlen($data['feedback']) > 1000) {
                $data['feedback_err'] = 'Feedback must be between 20 and 1000 characters';
            }

            if (!$data['consent']) {
                $data['consent_err'] = 'You must agree to publish your feedback';
            }

            // Check rate limiting (3 submissions per IP per day)
            $submissionsCount = $this->testimonialModel->getSubmissionsCountByIp($data['ip_address']);
            if ($submissionsCount >= 3) {
                $data['general_err'] = 'You have reached the maximum number of submissions for today. Please try again tomorrow.';
            }
            
            // Honeypot check
            if (!empty($_POST['honeypot'])) {
                // Silently reject if honeypot is filled
                $data['general_err'] = 'Invalid submission.';
            }

            // Handle profile photo upload (single image for author avatar)
            $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
            $max_size = 5 * 1024 * 1024; // 5 MB
            $upload_dir = APPROOT . '/../public/uploads/testimonials/';

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Check $_FILES['photo'] or single from $_FILES['photos']
            $fileToProcess = null;
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
                $fileToProcess = $_FILES['photo'];
            } elseif (isset($_FILES['photos']) && is_array($_FILES['photos']['name']) && isset($_FILES['photos']['name'][0]) && $_FILES['photos']['error'][0] !== UPLOAD_ERR_NO_FILE) {
                $fileToProcess = [
                    'name' => $_FILES['photos']['name'][0],
                    'type' => $_FILES['photos']['type'][0],
                    'tmp_name' => $_FILES['photos']['tmp_name'][0],
                    'error' => $_FILES['photos']['error'][0],
                    'size' => $_FILES['photos']['size'][0]
                ];
            }

            if ($fileToProcess) {
                if ($fileToProcess['error'] !== UPLOAD_ERR_OK) {
                    $data['photo_err'] = 'Failed to upload profile photo.';
                } else {
                    $ext = strtolower(pathinfo($fileToProcess['name'], PATHINFO_EXTENSION));
                    $validExt = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);

                    if (!in_array($fileToProcess['type'], $allowed_types) && !$validExt) {
                        $data['photo_err'] = 'Invalid file type. Only JPG, PNG, and WebP are allowed.';
                    } elseif ($fileToProcess['size'] > $max_size) {
                        $data['photo_err'] = 'Profile photo must be less than 5MB.';
                    } else {
                        $new_filename = 'avatar_' . uniqid() . '.' . ($ext ?: 'jpg');
                        if (move_uploaded_file($fileToProcess['tmp_name'], $upload_dir . $new_filename)) {
                            $data['photo'] = $new_filename;
                        } else {
                            $data['photo_err'] = 'Failed to save profile photo.';
                        }
                    }
                }
            }

            // Make sure errors are empty
            if (empty($data['full_name_err']) && empty($data['email_err']) && empty($data['rating_err']) && empty($data['feedback_err']) && empty($data['consent_err']) && empty($data['photo_err']) && empty($data['general_err'])) {
                // Add feedback
                if ($this->testimonialModel->addFeedback($data)) {
                    $data['success_msg'] = 'Thank you! Your feedback has been submitted successfully and is pending review.';
                    // Clear form fields
                    $data['full_name'] = '';
                    $data['company'] = '';
                    $data['designation'] = '';
                    $data['email'] = '';
                    $data['rating'] = '';
                    $data['feedback'] = '';
                    $data['consent'] = 0;
                    $data['photo'] = null;
                } else {
                    $data['general_err'] = 'Something went wrong. Please try again later.';
                }
            } else {
                // If validation failed, remove newly uploaded profile photo
                if (!empty($data['photo']) && file_exists($upload_dir . $data['photo'])) {
                    @unlink($upload_dir . $data['photo']);
                }
                $data['photo'] = null;
            }
            
            // Return JSON if submitted via AJAX
            if (!empty($_POST['is_ajax']) || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
                header('Content-Type: application/json');
                if (!empty($data['success_msg'])) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => $data['success_msg']
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'errors' => array_filter([
                            'full_name' => $data['full_name_err'] ?? '',
                            'email' => $data['email_err'] ?? '',
                            'rating' => $data['rating_err'] ?? '',
                            'feedback' => $data['feedback_err'] ?? '',
                            'consent' => $data['consent_err'] ?? '',
                            'photo' => $data['photo_err'] ?? '',
                            'general' => $data['general_err'] ?? ''
                        ])
                    ]);
                }
                exit;
            }
            
            // Get testimonials to display
            $testimonials = $this->testimonialModel->getApprovedTestimonials();
            $data['testimonials'] = $testimonials;
            $data['title'] = 'Testimonials - Workiify';
            
            $this->view('pages/testimonials', $data);
            
        } else {
            // Get testimonials
            $testimonials = $this->testimonialModel->getApprovedTestimonials();

            $data = [
                'title' => 'Testimonials - Workiify',
                'testimonials' => $testimonials,
                'full_name' => '',
                'company' => '',
                'designation' => '',
                'email' => '',
                'rating' => '',
                'feedback' => '',
                'consent' => 0,
                'full_name_err' => '',
                'email_err' => '',
                'rating_err' => '',
                'feedback_err' => '',
                'consent_err' => '',
                'photo_err' => '',
                'general_err' => '',
                'success_msg' => ''
            ];

            $this->view('pages/testimonials', $data);
        }
    }
}
