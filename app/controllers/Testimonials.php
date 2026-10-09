<?php
class Testimonials extends Controller {
    public function __construct() {
        $this->testimonialModel = $this->model('Testimonial');
    }

    public function index() {
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
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

            // Handle photo upload
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
                $max_size = 2 * 1024 * 1024; // 2 MB

                if (!in_array($_FILES['photo']['type'], $allowed_types)) {
                    $data['photo_err'] = 'Invalid file type. Only JPG, PNG, and WebP are allowed.';
                } elseif ($_FILES['photo']['size'] > $max_size) {
                    $data['photo_err'] = 'File size must be less than 2MB.';
                } else {
                    $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                    $new_filename = uniqid('testimonial_') . '.' . $file_extension;
                    $upload_dir = APPROOT . '/../public/uploads/testimonials/';
                    
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                    
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $new_filename)) {
                        $data['photo'] = $new_filename;
                    } else {
                        $data['photo_err'] = 'Failed to upload photo.';
                    }
                }
            }

            // Make sure errors are empty
            if (empty($data['full_name_err']) && empty($data['email_err']) && empty($data['rating_err']) && empty($data['feedback_err']) && empty($data['consent_err']) && empty($data['photo_err']) && empty($data['general_err'])) {
                // Add feedback
                if ($this->testimonialModel->addFeedback($data)) {
                    // Send Email to Workiify team (simplified implementation)
                    // mail('admin@workiify.com', 'New Testimonial Submission', 'A new testimonial is pending approval.');
                    
                    $data['success_msg'] = 'Thank you! Your feedback has been submitted successfully and is pending review.';
                    // Clear form fields
                    $data['full_name'] = '';
                    $data['company'] = '';
                    $data['designation'] = '';
                    $data['email'] = '';
                    $data['rating'] = '';
                    $data['feedback'] = '';
                    $data['consent'] = 0;
                } else {
                    $data['general_err'] = 'Something went wrong. Please try again later.';
                }
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
