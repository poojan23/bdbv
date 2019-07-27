<?php

class ModelLocalisationCountry extends PT_Model
{
    public function getCountry($country_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");

        return $query->row;
    }

    public function getCountries($data = array()) {
        if($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "country";

            $sort_data = array(
                'name',
                'iso_code_2',
                'iso_code_3'
            );

            if(isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY name";
            }

            if(isset($data['order']) && ($data['order'] != 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }

            if(isset($data['start']) || isset($data['limit'])) {
                if($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }

            $query = $this->db->query($sql);

            return $query->rows;
        } else {
            $country_data = $this->cache->get('country.member');

            if(!$country_data) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country ORDER BY name ASC");

                $country_data = $query->rows;

                $this->cache->set('country.member', $country_data);
            }

            return $country_data;
        }
    }

    public function getTotalCountries() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "country");

        return $query->row['total'];
    }
}