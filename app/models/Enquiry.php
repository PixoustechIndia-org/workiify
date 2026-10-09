<?php
class Enquiry {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function addEnquiry($data) {
        $this->db->query('INSERT INTO enquiries (source, company_name, company_address, contact_no, email, req_type, req_value, additional, email_sent, ip_address) VALUES (:source, :company_name, :company_address, :contact_no, :email, :req_type, :req_value, :additional, :email_sent, :ip_address)');

        $this->db->bind(':source', $data['source']);
        $this->db->bind(':company_name', $data['company_name']);
        $this->db->bind(':company_address', $data['company_address']);
        $this->db->bind(':contact_no', $data['contact_no']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':req_type', $data['req_type']);
        $this->db->bind(':req_value', $data['req_value']);
        $this->db->bind(':additional', $data['additional']);
        $this->db->bind(':email_sent', $data['email_sent']);
        $this->db->bind(':ip_address', $data['ip_address']);

        return $this->db->execute();
    }

    public function getAllEnquiries() {
        $this->db->query('SELECT * FROM enquiries ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function getEnquiryById($id) {
        $this->db->query('SELECT * FROM enquiries WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateStatus($id, $status) {
        $this->db->query('UPDATE enquiries SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getNewCount() {
        $this->db->query("SELECT COUNT(*) as count FROM enquiries WHERE status = 'New'");
        $row = $this->db->single();
        return $row->count;
    }
}
