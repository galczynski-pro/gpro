<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class Prompts extends Action{
	public function __construct(){
		parent::__construct('prompts'); 
	}

    public function getList(?string $order = NULL, int $limit = 999999) {
        return $this->query("SELECT * FROM $this->table ORDER BY item_order ASC LIMIT $limit");
    }

    public function getListByIDS($ids) {
        return $this->query("SELECT * FROM $this->table WHERE id IN ($ids)");
    }

    public function getListFront() {
        return $this->query("SELECT id, name, slug, image, expert FROM $this->table WHERE status='1' ORDER BY item_order ASC");
    }

    public function getMaxOrder() {
        return $this->query("SELECT MAX(item_order) AS max_order FROM $this->table")->Fetch();
    }

    public function getBySlug(?string $slug) {
        return $this->query("SELECT * FROM $this->table WHERE slug='$slug' AND status='1' LIMIT 1")->Fetch();
    }  

    public function checkVipByIdPrompt(?int $id_prompt) {
        return $this->query("SELECT * FROM prompts_credits_packs WHERE id_prompt = $id_prompt");
    }     
    
    // Search across prompts for front: optional q (text) and alpha (A-Z)
    public function searchFront(?string $q = null, ?string $alpha = null) {
        $where = "status='1'";

        if (!is_null($alpha) && $alpha !== '') {
            $alpha = strtoupper(substr($alpha, 0, 1));
            if (preg_match('/^[A-Z]$/', $alpha)) {
                $where .= " AND name LIKE '".$alpha."%'";
            }
        }

        if (!is_null($q) && trim($q) !== '') {
            $q = addslashes(trim($q));
            $like = "%$q%"; // informational; concatenated inline below
            $where .= " AND (name LIKE '%$q%' OR expert LIKE '%$q%' OR description LIKE '%$q%')";
        }

        $sql = "SELECT id, name, slug, image, expert FROM $this->table WHERE $where ORDER BY item_order ASC, name ASC";
        return $this->query($sql);
    }

    // Search within a fixed ID set (comma-separated) for category pages
    public function searchFrontByIds(string $ids, ?string $q = null, ?string $alpha = null) {
        // Validate ids: only digits and commas
        if (!preg_match('/^\d+(,\d+)*$/', str_replace(' ', '', $ids))) {
            return $this->query("SELECT id, name, slug, image, expert FROM $this->table WHERE 1=0");
        }

        $where = "status='1' AND id IN ($ids)";

        if (!is_null($alpha) && $alpha !== '') {
            $alpha = strtoupper(substr($alpha, 0, 1));
            if (preg_match('/^[A-Z]$/', $alpha)) {
                $where .= " AND name LIKE '".$alpha."%'";
            }
        }

        if (!is_null($q) && trim($q) !== '') {
            $q = addslashes(trim($q));
            $where .= " AND (name LIKE '%$q%' OR expert LIKE '%$q%' OR description LIKE '%$q%')";
        }

        $sql = "SELECT id, name, slug, image, expert FROM $this->table WHERE $where ORDER BY item_order ASC, name ASC";
        return $this->query($sql);
    }
     
}
