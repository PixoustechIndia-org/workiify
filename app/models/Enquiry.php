<?php
class Enquiry {
    private $db;

    // Where an enquiry came from. The first three are the website's own forms; the rest are
    // for leads an admin records by hand (phone calls, walk-ins ...).
    public static function sources() {
        return [
            'home' => 'Home page form',
            'contact' => 'Contact page form',
            'popup' => 'Website popup',
            'phone' => 'Phone call',
            'walkin' => 'Walk-in',
            'email' => 'Email',
            'other' => 'Other',
        ];
    }

    public static function webSources() {
        return ['home', 'contact', 'popup'];
    }

    public static function statuses() {
        return ['New', 'Contacted', 'Closed'];
    }

    // Columns the admin list can be sorted by (request value => SQL column).
    private static $sortable = [
        'date' => 'created_at',
        'company' => 'company_name',
        'status' => 'status',
        'source' => 'source',
    ];

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

    // Admin-entered enquiry; returns the new id or false.
    public function create(array $data) {
        $this->db->query('INSERT INTO enquiries (source, company_name, company_address, contact_no, email, req_type, req_value, additional, status, email_sent) VALUES (:source, :company_name, :company_address, :contact_no, :email, :req_type, :req_value, :additional, :status, 0)');
        $this->bindEditable($data);
        $this->db->bind(':status', $data['status']);
        return $this->db->execute() ? (int)$this->db->lastInsertId() : false;
    }

    public function update($id, array $data) {
        $this->db->query('UPDATE enquiries SET source = :source, company_name = :company_name, company_address = :company_address, contact_no = :contact_no, email = :email, req_type = :req_type, req_value = :req_value, additional = :additional, status = :status WHERE id = :id');
        $this->bindEditable($data);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':id', (int)$id);
        return $this->db->execute();
    }

    private function bindEditable(array $data) {
        foreach (['source', 'company_name', 'company_address', 'contact_no', 'email', 'req_type', 'additional'] as $k) {
            $this->db->bind(':' . $k, (string)$data[$k]);
        }
        $this->db->bind(':req_value', (int)$data['req_value']);
    }

    public function delete($id) {
        $this->db->query('DELETE FROM enquiries WHERE id = :id');
        $this->db->bind(':id', (int)$id);
        return $this->db->execute();
    }

    public function getAllEnquiries() {
        $this->db->query('SELECT * FROM enquiries ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function getRecent($limit = 5) {
        $this->db->query('SELECT * FROM enquiries ORDER BY created_at DESC, id DESC LIMIT ' . (int)$limit);
        return $this->db->resultSet();
    }

    // Filtered, sorted, paginated list for the admin. Returns [rows, total].
    public function search(array $f, $sort, $dir, $page, $perPage) {
        $where = [];
        $params = [];
        if ($f['q'] !== '') {
            $where[] = '(company_name LIKE :q1 OR email LIKE :q2 OR contact_no LIKE :q3 OR company_address LIKE :q4)';
            foreach (['q1', 'q2', 'q3', 'q4'] as $p) $params[':' . $p] = '%' . $f['q'] . '%';
        }
        if ($f['status'] !== '') { $where[] = 'status = :status'; $params[':status'] = $f['status']; }
        if ($f['source'] !== '') { $where[] = 'source = :source'; $params[':source'] = $f['source']; }
        if ($f['from'] !== '') { $where[] = 'created_at >= :from'; $params[':from'] = $f['from'] . ' 00:00:00'; }
        if ($f['to'] !== '') { $where[] = 'created_at <= :to'; $params[':to'] = $f['to'] . ' 23:59:59'; }
        $sql = $where ? ' WHERE ' . implode(' AND ', $where) : '';

        $this->db->query('SELECT COUNT(*) AS c FROM enquiries' . $sql);
        foreach ($params as $k => $v) $this->db->bind($k, $v);
        $total = (int)$this->db->single()->c;

        $col = self::$sortable[$sort] ?? 'created_at';
        $dir = $dir === 'asc' ? 'ASC' : 'DESC';
        $this->db->query('SELECT * FROM enquiries' . $sql . " ORDER BY $col $dir, id $dir LIMIT " . (int)$perPage . ' OFFSET ' . (int)(($page - 1) * $perPage));
        foreach ($params as $k => $v) $this->db->bind($k, $v);
        return [$this->db->resultSet(), $total];
    }

    public static function sortKeys() {
        return array_keys(self::$sortable);
    }

    public function statusCounts() {
        $this->db->query('SELECT status, COUNT(*) AS c FROM enquiries GROUP BY status');
        $out = ['all' => 0];
        foreach (self::statuses() as $s) $out[$s] = 0;
        foreach ($this->db->resultSet() as $r) {
            $out[$r->status] = (int)$r->c;
            $out['all'] += (int)$r->c;
        }
        return $out;
    }

    public function getEnquiryById($id) {
        $this->db->query('SELECT * FROM enquiries WHERE id = :id');
        $this->db->bind(':id', (int)$id);
        return $this->db->single();
    }

    public function updateStatus($id, $status) {
        $this->db->query('UPDATE enquiries SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', (int)$id);
        return $this->db->execute();
    }

    // How many enquiries one IP address sent in the last N minutes (spam / flood protection).
    public function countRecentByIp($ip, $minutes) {
        $this->db->query('SELECT COUNT(*) AS c FROM enquiries WHERE ip_address = :ip AND created_at >= DATE_SUB(NOW(), INTERVAL ' . (int)$minutes . ' MINUTE)');
        $this->db->bind(':ip', (string)$ip);
        return (int)$this->db->single()->c;
    }

    public function getNewCount() {
        $this->db->query("SELECT COUNT(*) as count FROM enquiries WHERE status = 'New'");
        $row = $this->db->single();
        return $row->count;
    }
}
