<?php

namespace Ddd\Application\Normalizer;

use FOS\RestBundle\Normalizer\ArrayNormalizerInterface;

class CategoryNormalizer implements ArrayNormalizerInterface
{
    public function normalize($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        if (isset($data['categories'])) {
            return array_merge(
                $data,
                ['categories' => $this->normalizeCategories($data['categories'])]
            );
        }

        return collect($data)
            ->map(function ($row) {
                if (!is_array($row)) {
                    return $row;
                }
                
                if (isset($row['categories'])) {
                    return array_merge(
                        $row,
                        ['categories' => $this->normalizeCategories($row['categories'])]
                    );
                }

                return $row;
            })
            ->all();
    }

    private function normalizeCategories($categories): array
    {
        if (!is_array($categories)) {
            return $categories;
        }

        if (!isset($categories['name'])) {
            return $categories;
        }

        return array_column($categories, 'name');
    }
}
