<?php
class Testimonial {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function addFeedback($data) {
        $this->db->query('INSERT INTO testimonials (full_name, company, designation, email, rating, feedback, photo, consent, ip_address) VALUES (:full_name, :company, :designation, :email, :rating, :feedback, :photo, :consent, :ip_address)');
        
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':company', $data['company']);
        $this->db->bind(':designation', $data['designation']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':feedback', $data['feedback']);
        $this->db->bind(':photo', $data['photo']);
        $this->db->bind(':consent', $data['consent']);
        $this->db->bind(':ip_address', $data['ip_address']);

        return $this->db->execute();
    }

    public function getApprovedTestimonials() {
        $this->db->query("SELECT * FROM testimonials WHERE status = 'Approved' ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    public function getHomeTestimonials() {
        $this->db->query("SELECT * FROM testimonials WHERE status = 'Approved' AND show_on_home = 1 ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    public function getAllTestimonials() {
        $this->db->query("SELECT * FROM testimonials ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    public function getTestimonialById($id) {
        $this->db->query("SELECT * FROM testimonials WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateTestimonial($data) {
        $this->db->query('UPDATE testimonials SET full_name = :full_name, company = :company, designation = :designation, rating = :rating, feedback = :feedback, status = :status, show_on_home = :show_on_home WHERE id = :id');
        
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':company', $data['company']);
        $this->db->bind(':designation', $data['designation']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':feedback', $data['feedback']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':show_on_home', $data['show_on_home']);
        $this->db->bind(':id', $data['id']);

        return $this->db->execute();
    }

    public function getSubmissionsCountByIp($ip) {
        $this->db->query("SELECT COUNT(*) as count FROM testimonials WHERE ip_address = :ip_address AND created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)");
        $this->db->bind(':ip_address', $ip);
        $row = $this->db->single();
        return $row->count;
    }
}
