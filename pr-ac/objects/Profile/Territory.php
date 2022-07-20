<?php

namespace Profile;

/**
 * Класс "Территория"
 * отвечает за города, регионы, кластеры и т.п.
 * @author p.zajcev@k-t.org
 * @version 1.0 (06.04.2022)
 */

class Territory
{

    /**
     * Преобразовать id городов в: названия городов и соответствующие названия кластеров     
     * @param array $cities список id городов
     * @param resource $db дескриптор соединения с БД
     * @return array список соответствующих названий городов и названий кластеров
     */
    public function decodeNames($cities, $db)
    {
        $asc = $this->allCityCluster($db);

        foreach ($asc as $asc_key => $asc_value) {

            if (count($cities) > 0 and in_array($asc_key, $cities)) {
                $names_city_cluster[$asc_key] = array(
                    'city_name'    => $asc_value['city_name'],
                    'cluster_name' => $asc_value['cluster_name']
                );
            }
        }
        return $names_city_cluster;
    }

    /**
     * Получить из БД связки всех id_group c названими городов и соответствующими названиями кластеров
     * @return array связки всех id_group c названими городов и соответствующими названиями кластеров     
     * @param resource $db дескриптор соединения с БД
     */
    public function allCityCluster($db)
    {
        $sql_all_city_cluster = 'SELECT
            `group`.id_group AS city_id,
            `group`.name AS city_name,
            cluster.name AS cluster_name
        FROM
            `group`
        LEFT JOIN
            cluster ON cluster.id = `group`.id_cluster
        ORDER BY
            cluster.name,
            `group`.name';

        $desc_all_city_cluster = mysql_query($sql_all_city_cluster, $db);

        while ($row = mysql_fetch_assoc($desc_all_city_cluster)) {

            $all_city_cluster[$row['city_id']] = array(
                'city_name' => $row['city_name'],
                'cluster_name' => $row['cluster_name']
            );
        }
        return $all_city_cluster;
    }
}
