<?php
class Testimonial {
    use ListQuery;

    private $db;

    public static function statuses() {
        return ['Pending', 'Approved', 'Rejected'];
    }

    // Columns the admin list can be sorted by (request value => SQL column).
    private static $sortable = [
        'date' => 'created_at',
        'name' => 'full_name',
        'rating' => 'rating',
        'status' => 'status',
    ];

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

    // Filtered, sorted, paginated list for the admin. Returns [rows, total].
    public function search(array $f, $sort, $dir, $page, $perPage) {
        $where = [];
        $params = [];
        if ($f['q'] !== '') {
            $where[] = '(full_name LIKE :q1 OR email LIKE :q2 OR company LIKE :q3 OR feedback LIKE :q4)';
            foreach (['q1', 'q2', 'q3', 'q4'] as $p) { $params[':' . $p] = '%' . $f['q'] . '%'; }
        }
        if ($f['status'] !== '') { $where[] = 'status = :status'; $params[':status'] = $f['status']; }
        if ($f['rating'] !== '') { $where[] = 'rating = :rating'; $params[':rating'] = (int)$f['rating']; }
        if ($f['featured'] !== '') { $where[] = 'show_on_home = :featured'; $params[':featured'] = (int)$f['featured']; }
        return $this->pagedRows('testimonials', $where, $params, self::$sortable[$sort] ?? 'created_at', $dir, $page, $perPage);
    }

    public static function sortKeys() {
        return array_keys(self::$sortable);
    }

    public function statusCounts() {
        return $this->countByStatus('testimonials', self::statuses());
    }

    public function getTestimonialById($id) {
        $this->db->query("SELECT * FROM testimonials WHERE id = :id");
        $this->db->bind(':id', (int)$id);
        return $this->db->single();
    }

    // Admin-entered testimonial (e.g. copied from a Google review); returns the new id or false.
    public function create(array $data) {
        $this->db->query('INSERT INTO testimonials (full_name, company, designation, email, rating, feedback, photo, consent, status, show_on_home) VALUES (:full_name, :company, :designation, :email, :rating, :feedback, :photo, 1, :status, :show_on_home)');
        $this->bindEditable($data);
        return $this->db->execute() ? (int)$this->db->lastInsertId() : false;
    }

    public function updateTestimonial($data) {
        $this->db->query('UPDATE testimonials SET full_name = :full_name, company = :company, designation = :designation, email = :email, rating = :rating, feedback = :feedback, photo = :photo, status = :status, show_on_home = :show_on_home WHERE id = :id');
        $this->bindEditable($data);
        $this->db->bind(':id', (int)$data['id']);
        return $this->db->execute();
    }

    private function bindEditable(array $data) {
        foreach (['full_name', 'company', 'designation', 'email', 'feedback', 'status'] as $k) {
            $this->db->bind(':' . $k, (string)$data[$k]);
        }
        $this->db->bind(':rating', (int)$data['rating']);
        $this->db->bind(':photo', $data['photo'] !== '' ? $data['photo'] : null);
        $this->db->bind(':show_on_home', (int)$data['show_on_home']);
    }

    public function setStatus($id, $status) {
        $this->db->query('UPDATE testimonials SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', (int)$id);
        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query('DELETE FROM testimonials WHERE id = :id');
        $this->db->bind(':id', (int)$id);
        return $this->db->execute();
    }

    // How many testimonials point at a photo file (seeded rows share avatars, so a file is
    // only removed from disk once nothing uses it any more).
    public function countPhotoUsage($photo) {
        $this->db->query('SELECT COUNT(*) AS c FROM testimonials WHERE photo = :p');
        $this->db->bind(':p', $photo);
        return (int)$this->db->single()->c;
    }

    public function getSubmissionsCountByIp($ip) {
        $this->db->query("SELECT COUNT(*) as count FROM testimonials WHERE ip_address = :ip_address AND created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)");
        $this->db->bind(':ip_address', $ip);
        $row = $this->db->single();
        return $row->count;
    }
}
