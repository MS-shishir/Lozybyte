<?php

/**
 * Visitor & customer data retention policy.
 *
 * These values document how long LozyByte keeps personal and analytical
 * data, and are surfaced on the Privacy Policy page. Tune as required by
 * your jurisdiction (e.g. GDPR / Bangladesh DP law).
 */
return [
    // How long anonymous visitor analytics (geo, device, pages viewed) are kept.
    'analytics_retention_days'   => 365,

    // How long captured sales leads (name, email, phone, message) are kept.
    'lead_retention_days'        => 730,

    // How long session data is retained (see config/session.php lifetime in minutes).
    'session_lifetime_minutes'   => (int) env('SESSION_LIFETIME', 120),

    // Cookies used by the site (for the cookie consent banner categories).
    'cookie_categories' => [
        'necessary' => [
            'label'       => 'Strictly Necessary',
            'always_on'   => true,
            'description' => 'Required for core functionality such as security, session management and load balancing. Cannot be disabled.',
        ],
        'analytics' => [
            'label'       => 'Analytics',
            'always_on'   => false,
            'description' => 'Helps us understand how visitors interact with the site (anonymous, aggregated statistics). Loaded only after consent.',
        ],
        'marketing' => [
            'label'       => 'Marketing',
            'always_on'   => false,
            'description' => 'Used to deliver relevant advertising and measure campaign performance (e.g. Meta Pixel, Google Ads). Loaded only after consent.',
        ],
    ],

    'contact_email' => env('MAIL_FROM_ADDRESS', 'privacy@lozybyte.com'),
];
