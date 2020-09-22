<?php declare(strict_types=1);

$data = [
    ['a','a.a','a.a.a','a.a.a.a','a.a.a.a.a'],
    ['b'],
    ['c','c.a'],
    ['','c.b','c.b.a'],
    ['','','c.b.b','c.b.b.a','c.b.b.a.a']
];

$result = [
    'a' => [
        'a.a' => [
            'a.a.a' => [
                'a.a.a.a' => [
                    'a.a.a.a.a' => [], // added this so the task is complete :)
                ]
            ]
        ]
    ],
    'b' => [],
    'c' => [
        'c.a' => [],
        'c.b' => [
            'c.b.a' => [],
            'c.b.b' => [
                'c.b.b.a' => [
                    'c.b.b.a.a' => []
                ]
            ]
        ]
    ]
];

$func = function (array $input): array {
    $result = [];

    foreach ($input as $row) {
        foreach ($row as $value) {
            if (!$value) {
                continue;
            }
            $parts = explode('.', $value);
            $ptr = &$result;
            $value = '';
            foreach ($parts as $part) {
                $value = $value ? $value . '.' . $part : $part;
                $next = $ptr[$value] ?? [];
                $ptr[$value] = $next;
                $ptr = &$ptr[$value];
            }
        }
    }

    return $result;
};

$myResult = $func($data);

if ($myResult === $result) {
    echo "BOTH ARRAYS ARE EQUAL\n";
} else {
    echo "FAILURE, DIFFERENT RESULT:\n\n";
    var_dump($myResult);
}
