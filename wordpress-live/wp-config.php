<?php

//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL
# Database Configuration
define( 'DB_NAME', 'wp_kimberbell' );
define( 'DB_USER', 'kimberbell' );
define( 'DB_PASSWORD', 'JkjBCs1I0O0znnjtLJ27' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_HOST_SLAVE', '127.0.0.1' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_';
define('WP_DEBUG', false);
# Security Salts, Keys, Etc
define('AUTH_KEY',         'Fa^f}&o6`U=QW:DA5PU01f|lIBt9/<0uUAN.}/L[h;PG3)RM~9FwXw%>Y(b6H4i~');
define('SECURE_AUTH_KEY',  'k&W.WJwyl>~CWY;Rph)g+u8R-LtCxj*J$G0{2#A]=+lB>r-cH|=@]U#LB,J7r[]H');
define('LOGGED_IN_KEY',    '#K{u1FWTY8*x5[8Y9cMx)}(F`P0ci;TNZp/0~T~lC{4DXyJMZ~yl4S7iE:=nU1Xq');
define('NONCE_KEY',        'oZ.}#2s=GSw5AS<WME=lV)>%p%*H-|k::eZw*-P/ EezPEJx+x([8Ch[W<6|w98c');
define('AUTH_SALT',        'En11RTy!tv6Xi1Ms/{1PHPWj#a+xuQCh;O^-=;)gOaLBvFu-xH#) uggWv: !zc<');
define('SECURE_AUTH_SALT', 'go.gy|U)w8t} no{oao]m|si>cLFh!H,ux%T|0]P 7$z0fn?i1VR8S3,}Y<1 xbL');
define('LOGGED_IN_SALT',   '` CC3j/B6s/9o&I|G7_g0CwD%DZ7 Q&t ^/5AP@F!)m,BNul[^MKGxy!+rM1TpK-');
define('NONCE_SALT',       '3-i_57l8!9w+@;nbjtS;sUk-B-#/arO]Ckis-H}<mT5L(Cz5jQMX(l-E[?B]>m=P');
# Localized Language Stuff
define( 'WP_CACHE', TRUE );
define( 'WP_AUTO_UPDATE_CORE', false );
define( 'PWP_NAME', 'kimberbell' );
define( 'FS_METHOD', 'direct' );
define( 'FS_CHMOD_DIR', 0775 );
define( 'FS_CHMOD_FILE', 0664 );
umask(0002);
define( 'WPE_APIKEY', '1fd5102080f57e162eba28c5d6cb0b0fdd866797' );
define( 'WPE_CLUSTER_ID', '158020' );
define( 'WPE_CLUSTER_TYPE', 'pod' );
define( 'WPE_ISP', true );
define( 'WPE_BPOD', false );
define( 'WPE_RO_FILESYSTEM', false );
define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );
define( 'WPE_SFTP_PORT', 2222 );
define( 'WPE_LBMASTER_IP', '' );
define( 'WPE_CDN_DISABLE_ALLOWED', false );
define( 'DISALLOW_FILE_MODS', FALSE );
define( 'DISALLOW_FILE_EDIT', FALSE );
define( 'DISABLE_WP_CRON', false );
define( 'WPE_FORCE_SSL_LOGIN', true );
define( 'FORCE_SSL_LOGIN', true );
/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/
define( 'WPE_EXTERNAL_URL', false );
define( 'WP_POST_REVISIONS', 5 );
define( 'WPE_WHITELABEL', 'wpengine' );
define( 'WP_TURN_OFF_ADMIN_BAR', false );
define( 'WPE_BETA_TESTER', false );
$wpe_cdn_uris=array ( );
$wpe_no_cdn_uris=array ( );
$wpe_content_regexs=array ( );
$wpe_all_domains=array ( 0 => 'kimberbell.wpengine.com', 1 => 'kimberbell.com', 2 => 'www.mykimberbell.com', 3 => 'mykimberbell.com', 4 => 'www.kimberbell.com', );
$wpe_varnish_servers=array ( 0 => 'pod-158020', );
$wpe_special_ips=array ( 0 => '34.82.85.146', );
$wpe_netdna_domains=array ( 0 =>  array ( 'zone' => '2rf7bz1p8r0jmqaohl6xkbbk', 'match' => 'kimberbell.com', 'secure' => false, 'dns_check' => '0', ), 1 =>  array ( 'zone' => '27oh9xmwsp13kv9ub1bb3w76', 'match' => 'kimberbell.wpengine.com', 'secure' => false, 'dns_check' => '0', ), 2 =>  array ( 'zone' => 'fyhaj2oti4wt1a1o29a0yh16', 'match' => 'www.kimberbell.com', 'secure' => false, 'dns_check' => '0', ), );
$wpe_netdna_domains_secure=array ( );
$wpe_netdna_push_domains=array ( );
$wpe_domain_mappings=array ( );
$memcached_servers=array ( );

define( 'WPE_SFTP_ENDPOINT', '' );
define('WPLANG','');
# WP Engine ID
# WP Engine Settings
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define( 'WP_MEMORY_LIMIT', '512M');
define( 'WP_MAX_MEMORY_LIMIT', '512M');
# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', __DIR__ . '/');
require_once(ABSPATH . 'wp-settings.php');
