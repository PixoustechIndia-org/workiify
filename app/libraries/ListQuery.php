<?php
/**
 * Shared admin-list queries for models whose table has a `status` column
 * (enquiries, testimonials). The model builds the WHERE parts; this runs them.
 * Expects the using class to have a `$db` Database property.
 */
trait ListQuery {
    // One page of rows plus the total match count. Returns [rows, total].
    protected function pagedRows($table, array $where, array $params, $col, $dir, $page, $perPage) {
        $sql = $where ? ' WHERE ' . implode(' AND ', $where) : '';

        $this->db->query('SELECT COUNT(*) AS c FROM ' . $table . $sql);
        $this->bindAll($params);
        $total = (int)$this->db->single()->c;

        $dir = $dir === 'asc' ? 'ASC' : 'DESC';
        $this->db->query('SELECT * FROM ' . $table . $sql . " ORDER BY $col $dir, id $dir LIMIT " . (int)$perPage . ' OFFSET ' . (int)(($page - 1) * $perPage));
        $this->bindAll($params);
        return [$this->db->resultSet(), $total];
    }

    // ['all' => n, '<status>' => n, ...] with every known status present (0 when unused).
    protected function countByStatus($table, array $statuses) {
        $this->db->query('SELECT status, COUNT(*) AS c FROM ' . $table . ' GROUP BY status');
        $out = ['all' => 0];
        foreach ($statuses as $s) {
            $out[$s] = 0;
        }
        foreach ($this->db->resultSet() as $r) {
            $out[$r->status] = (int)$r->c;
            $out['all'] += (int)$r->c;
        }
        return $out;
    }

    private function bindAll(array $params) {
        foreach ($params as $k => $v) {
            $this->db->bind($k, $v);
        }
    }
}
