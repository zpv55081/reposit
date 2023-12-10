<?php

namespace App\Repository;

use Doctrine\Persistence\ManagerRegistry;

class ShowcaseRepository
{
    public static function getAll(ManagerRegistry $doctrine): array
    {
        $replies = $doctrine->getConnection()->fetchAllAssociative('SELECT
            showcase.id,
            vehicle_brand.name as brand,
            vehicle_model.name as model,
            showcase.image,
            showcase.price
            FROM
                showcase
            LEFT JOIN 
                vehicle_model ON (vehicle_model.id = showcase.vehicle_model_id)
            LEFT JOIN
                vehicle_brand ON (vehicle_brand.id = vehicle_model.vehicle_brand_id)
            ORDER BY brand, model ASC
        ');

        return $replies;
    }
}
