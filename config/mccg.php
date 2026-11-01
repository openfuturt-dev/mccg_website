<?php

$otherOffices = array_values(array_filter(array_map(
    'trim',
    explode(',', (string) env('MCCG_OTHER_OFFICES', 'Casablanca, Dubai'))
)));

return [
    'phone' => env('MCCG_PHONE', '05 24 43 83 70'),
    'email' => env('MCCG_EMAIL', 'contact@mc-cg.com'),
    'privacy_email' => env('MCCG_PRIVACY_EMAIL', 'contact@mc-cg.com'),
    'street_address' => env('MCCG_STREET_ADDRESS', '92, Bd Zerktouni, Appt 6, 2ème étage'),
    'address' => env('MCCG_ADDRESS', '92, Bd Zerktouni, Appt 6, 2ème étage, Guéliz, Marrakech'),
    'city' => env('MCCG_CITY', 'Marrakech'),
    'region' => env('MCCG_REGION', 'Marrakech-Safi'),
    'other_offices' => $otherOffices,
    'hours' => env('MCCG_HOURS', 'Lun - Ven: 9h00 - 18h00'),
    'linkedin_url' => env('MCCG_LINKEDIN_URL', 'https://www.linkedin.com/in/majdchraibi'),
    'instagram_url' => env('MCCG_INSTAGRAM_URL', 'https://www.instagram.com/mccg.consulting'),
    'google_maps_url' => env('MCCG_GOOGLE_MAPS_URL'),
    'google_maps_embed_url' => env('MCCG_GOOGLE_MAPS_EMBED_URL'),
    'analytics_provider' => strtolower((string) env('MCCG_ANALYTICS_PROVIDER', 'google')),
    'google_tag_id' => env('MCCG_GOOGLE_TAG_ID', env('MCCG_GA_ID', 'AW-987825813')),
    'google_analytics_id' => env('MCCG_GOOGLE_ANALYTICS_ID', 'G-E0GCGNTBLL'),
    'google_tag_manager_id' => env('MCCG_GOOGLE_TAG_MANAGER_ID', 'GTM-N64QTKK'),
    'google_ads_conversion_send_to' => env('MCCG_GOOGLE_ADS_CONVERSION_SEND_TO', 'AW-987825813/g_upCMH2m7sYEJWNhNcD'),
    'plausible_domain' => env('MCCG_PLAUSIBLE_DOMAIN'),
    'office_locations' => [
        [
            'city' => 'Marrakech',
            'address' => env('MCCG_ADDRESS', '92, Bd Zerktouni, Appt 6, 2ème étage, Guéliz, Marrakech'),
            'maps_url' => env('MCCG_MARRAKECH_MAPS_URL', 'https://www.google.com/maps/place/31°38\'14.2%22N+8°00\'41.8%22W/@31.637271,-8.011621,17z/data=!4m4!3m3!8m2!3d31.6372705!4d-8.0116213?hl=en-GB&entry=ttu&g_ep=EgoyMDI2MDYyOS4wIKXMDSoASAFQAw%3D%3D'),
            'embed_url' => env('MCCG_MARRAKECH_MAPS_EMBED_URL', 'https://www.google.com/maps?q=31.6372705,-8.0116213&z=15&output=embed'),
        ],
        [
            'city' => 'Casablanca',
            'address' => 'Bureau MCCG Casablanca',
            'maps_url' => env('MCCG_CASABLANCA_MAPS_URL', 'https://www.google.com/maps?q=33.5846519,-7.6528649&z=17&hl=en'),
            'embed_url' => env('MCCG_CASABLANCA_MAPS_EMBED_URL', 'https://www.google.com/maps?q=33.5846519,-7.6528649&z=15&output=embed'),
        ],
        [
            'city' => 'Dubai',
            'address' => 'Bureau MCCG Dubai',
            'maps_url' => env('MCCG_DUBAI_MAPS_URL', 'https://www.google.com/maps/place/25°10\'38.4%22N+55°16\'24.3%22E/@25.177329,55.273418,17z/data=!4m4!3m3!8m2!3d25.1773294!4d55.273418?hl=en-GB&entry=ttu&g_ep=EgoyMDI2MDYyOS4wIKXMDSoASAFQAw%3D%3D'),
            'embed_url' => env('MCCG_DUBAI_MAPS_EMBED_URL', 'https://www.google.com/maps?q=25.1773294,55.273418&z=15&output=embed'),
        ],
    ],
];
