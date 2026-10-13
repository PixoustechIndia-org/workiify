<?php
class Contact_us extends Controller {
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->submitEnquiry();
            return;
        }

        $contentModel = $this->model('Home_content_model');
        $contentModel->setPage('contact');

        $fieldKeys = [
            'contact_banner_image', 'contact_banner_heading', 'contact_banner_tagline',
            'contact_address', 'contact_phone_display', 'contact_phone_link',
            'contact_email_1', 'contact_email_2', 'contact_access', 'contact_map_query',
            'contact_form_heading', 'contact_form_subtitle',
        ];

        $data = [
            'title' => 'Contact Us - Workiify',
            'hideTourBand' => true,
            'f' => $contentModel->getFields($fieldKeys),
        ];
        $this->view('pages/contact_us', $data);
    }

    /**
     * Shared handler for the enquiry form -- used by the inline forms on the
     * Home and Contact pages, and by the site-wide enquiry popup. Always
     * responds with JSON so all three can show an inline result without a
     * full page reload.
     */
    private function submitEnquiry() {
        header('Content-Type: application/json');

        $companyName = trim($_POST['companyName'] ?? '');
        $companyAddress = trim($_POST['companyAddress'] ?? '');
        $contactNo = trim($_POST['contactNo'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $reqType = trim($_POST['reqType'] ?? '');
        $reqValue = trim($_POST['reqValue'] ?? '');
        $additional = trim($_POST['additional'] ?? '');
        $source = trim($_POST['source'] ?? 'contact');
        if (!in_array($source, ['home', 'contact', 'popup'], true)) $source = 'contact';

        $errors = [];
        if ($companyName === '') $errors[] = 'Company name is required.';
        if (mb_strlen($companyName) > 150) $errors[] = 'Company name is too long.';
        if ($companyAddress === '') $errors[] = 'Company address is required.';
        if (mb_strlen($companyAddress) > 250) $errors[] = 'Company address is too long.';
        if (!preg_match('/^[6-9][0-9]{9}$/', $contactNo)) $errors[] = 'Enter a valid 10-digit contact number.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
        if (!in_array($reqType, ['space', 'seats'], true)) $errors[] = 'Select a requirement type.';
        if (!ctype_digit((string) $reqValue) || (int) $reqValue < 1) $errors[] = 'Enter a valid requirement value.';
        if (mb_strlen($additional) > 1000) $errors[] = 'Additional requirements text is too long.';

        if (!empty($errors)) {
            echo json_encode(['success' => false, 'message' => $errors[0]]);
            exit;
        }

        // Flood protection: only accept submissions made from this site, and cap how often one
        // visitor can send (real visitors never come close to these limits).
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        if ($origin !== '' && parse_url($origin, PHP_URL_HOST) !== parse_url('http://' . ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST)) {
            echo json_encode(['success' => false, 'message' => 'Something went wrong sending your enquiry. Please try WhatsApp or call us directly.']);
            exit;
        }
        $enquiryModel = $this->model('Enquiry');
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        if ($ip !== '' && ($enquiryModel->countRecentByIp($ip, 10) >= 5 || $enquiryModel->countRecentByIp($ip, 1440) >= 20)) {
            echo json_encode(['success' => false, 'message' => 'You have sent several enquiries already. Our team will get back to you shortly. For anything urgent, please WhatsApp or call us.']);
            exit;
        }

        $contentModel = $this->model('Home_content_model');
        $contentModel->setPage('contact');
        $f = $contentModel->getFields(['contact_email_1', 'contact_email_2']);
        $to = $f['contact_email_1'] ?? '';

        $reqLabel = $reqType === 'space' ? 'Required space (sq. ft.)' : 'Seats (nos.)';
        $sent = false;

        if ($to !== '') {
            $subject = 'New enquiry from ' . $companyName . ' - Workiify website';
            $body = "You have a new enquiry from the Workiify website:\r\n\r\n"
                  . 'Source: ' . ucfirst($source) . " page\r\n"
                  . "Company name: {$companyName}\r\n"
                  . "Company address: {$companyAddress}\r\n"
                  . "Contact number: {$contactNo}\r\n"
                  . "Email: {$email}\r\n"
                  . "Requirement: {$reqLabel} - {$reqValue}\r\n"
                  . 'Additional requirements: ' . ($additional !== '' ? $additional : '-') . "\r\n";

            $headers = 'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM . ">\r\n";
            $headers .= "Reply-To: {$email}\r\n";
            if (!empty($f['contact_email_2'])) {
                $headers = "Cc: {$f['contact_email_2']}\r\n" . $headers;
            }

            // Envelope sender (Return-Path) = the same no-reply address, so receiving servers can verify the
            // mail really comes from this domain (otherwise Gmail shows "via <server name>").
            $envelope = filter_var(MAIL_FROM, FILTER_VALIDATE_EMAIL) ? '-f' . MAIL_FROM : '';
            $sent = $envelope !== '' ? @mail($to, $subject, $body, $headers, $envelope) : @mail($to, $subject, $body, $headers);
        }

        // Always record the enquiry so it shows up in the admin panel, even
        // if the notification email couldn't be sent -- a lead should never
        // be lost to a mail delivery problem.
        $saved = $enquiryModel->addEnquiry([
            'source' => $source,
            'company_name' => $companyName,
            'company_address' => $companyAddress,
            'contact_no' => $contactNo,
            'email' => $email,
            'req_type' => $reqType,
            'req_value' => $reqValue,
            'additional' => $additional,
            'email_sent' => $sent ? 1 : 0,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);

        if ($saved) {
            echo json_encode(['success' => true, 'message' => 'Thank you! Your enquiry has been sent. Our team will get back to you shortly.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Something went wrong sending your enquiry. Please try WhatsApp or call us directly.']);
        }
        exit;
    }
}
