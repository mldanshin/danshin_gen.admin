<?php

return [
    'unavailable' => [
        'label' => 'Недоступный?',
    ],
    'live' => [
        'label' => 'Живой?',
    ],
    'gender' => [
        'label' => 'Пол',
        'items' => [
            1 => 'Незвестный',
            2 => 'Мужской',
            3 => 'Женский',
        ],
    ],
    'surname' => [
        'label' => 'Фамилия',
    ],
    'old_surname' => [
        'label' => 'Прежние фамилии',
        'order' => 'Порядковый номер',
        'name' => 'Фамилия',
    ],
    'name' => [
        'label' => 'Имя',
    ],
    'patronymic' => [
        'label' => 'Отчество',
    ],
    'has_patronymic' => [
        'label' => 'Имеет отчество?',
    ],
    'birth_date' => [
        'label' => 'Дата рождения',
        'rule' => 'Строка в формате ГГГГ-ММ-ДД, неизвестная цифра заменяется на ?. Остаьте строку пустой, если данные неизвестны.',
    ],
    'birth_place' => [
        'label' => 'Место рождения',
    ],
    'death_date' => [
        'label' => 'Дата смерти',
        'rule' => 'Строка в формате ГГГГ-ММ-ДД, неизвестная цифра заменяется на ?. Остаьте строку пустой, если данные неизвестны.',
    ],
    'burial_place' => [
        'label' => 'Место захоронения',
    ],
    'note' => [
        'label' => 'Примечание',
    ],
    'activities' => [
        'label' => 'Вид деятельности',
    ],
    'emails' => [
        'label' => 'Электронная почта',
    ],
    'internet' => [
        'label' => 'Интернет ресурсы',
        'name' => 'Наименование',
        'url' => 'URL',
    ],
    'phones' => [
        'label' => 'Телефоны',
        'rule' => 'Номер в формате +79998880011',
    ],
    'residences' => [
        'label' => 'Места проживания',
        'name' => 'Наименование',
        'date' => 'Дата актуальности данных',
        'date_rule' => 'Строка в формате ГГГГ-ММ-ДД, неизвестная цифра заменяется на ?. Остаьте строку пустой, если данные неизвестны.',
    ],
    'parents' => [
        'label' => 'Родители',
        'role' => 'Роль',
        'person' => 'Лицо',
        'items' => [
            '1' => 'Не известно',
            '2' => 'Отец',
            '3' => 'Мать',
        ],
        'info' => 'Выбирите сначала роль',
    ],
    'marriages' => [
        'label' => 'Брак (сожительство)',
        'role' => 'Роль лица',
        'role_soulmate' => 'Роль второго лица',
        'person' => 'Лицо',
        'items' => [
            '1' => 'Партнёр',
            '2' => 'Сожитель',
            '3' => 'Сожительница',
            '4' => 'Муж',
            '5' => 'Жена',
        ],
        'info_not_role' => 'Выбирите роль лица',
    ],
    'photo' => [
        'label' => 'Фото',
        'date' => 'Дата снимка',
        'date_rule' => 'Строка в формате ГГГГ-ММ-ДД, неизвестная цифра заменяется на ?. Остаьте строку пустой, если данные неизвестны.',
        'order' => 'Порядковый номер',
        'file' => 'Загрузить файл',
        'file_rule' => 'Допустимы файлы в формате webp',
    ],
    'person_short' => [
        'surname_unknown' => '?Фамилия?',
        'name_unknown' => '?Имя?',
        'patronymic_unknown' => '?Отчество?',
    ],
];
