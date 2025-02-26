<?php

return [
    'font_dir' => storage_path('fonts/'), // โฟลเดอร์เก็บฟอนต์
    'font_cache' => storage_path('fonts/'), // โฟลเดอร์แคชฟอนต์
    'default_font' => 'THSarabunNew', // กำหนดฟอนต์ไทยเป็นค่าเริ่มต้น

    'fonts' => [
        'THSarabunNew' => [
            'R'  => 'THSarabunNew.ttf',    // Regular
            'B'  => 'THSarabunNew-Bold.ttf', // Bold
            'I'  => 'THSarabunNew-Italic.ttf', // Italic
            'BI' => 'THSarabunNew-BoldItalic.ttf' // Bold Italic
        ],
    ],
];
