<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'sthrtrav_wor0909');

/** MySQL database username */
define('DB_USER', 'sthrtrav_wor0909');

/** MySQL database password */
define('DB_PASSWORD', 'a4EHKF6c');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'wg!CQ);(HpGri}B/UVqA+Si)tNu{jO}wDulLPI?RQN}hh%pvQyc|$xEveWdslW/bqh_YX(SG@z>[Xpuh$)vrWyIq&NB[cldS>&TYQm+[zidiA[U};N+cFBZ||pzQ}!cI');
define('SECURE_AUTH_KEY', 'UfIm&$I*]k(Ndu^B_fMsv!x@I]+WxugWjzErb@n[GuQgFj>}SL@}xLQYmOwk(LBe;=yb/zbws>rU/MU(YhyqP=iTn^NJ>etgqX}yN*uSI]pj<O*)f>fH;VmeM)^$Wt|]');
define('LOGGED_IN_KEY', '(rmM;mYtpN+MKaSF-iETC{@tm[z!vf;jsTu)vDFzo!NnrBt!_Vlf+^tXo_PxqnYtXhpl^J}QCEzcwjkTLLvijy%Ro_%JmoYKzyrkxWp]zaScGwADruinU/+evZ}iivGe');
define('NONCE_KEY', 'eB]g*/WGn>YLphEHuJLfOakDn]i/UrW(s!VbdZsp[E]l^<j%oL|rBCen}A-ny=maSo}KPV|g$zx<n_HW(TSW(ID;|yHd<>tzu@K+SON@qbiZXK@@GjSkg{e-dc+|RHgc');
define('AUTH_SALT', 'lD}zlHe>)n|luXMb|(GhN=nq&Op>HYl^I)UZ&P)$oth;zq>MyU$f|CUo[jlCLR|>%x!YVtrl)sPZ*=dcN;({d;fP&]Bho/f};NimPo/-dtwaewPUzxvVi/+e%IIsZDE=');
define('SECURE_AUTH_SALT', ';N!w@$>ooeHWrh>PP|<)mL(_d!_jN-J+N+-ElDUC%HfWWh(nxD)?lfj[c^%+hvUMHg=!%dN){SWrr![(x{idgsqa[!Lmvy}[;XBtTEwZ>[n{@vENAbJ=L;LKKdEPu>nI');
define('LOGGED_IN_SALT', 'hu[gCOkm=t]EOh;ZKecV}!{Cx)]_<DnMZ/BF@T(d-W/O](sYB^?f]<&>BDlS}g*<j$a?lVfrW!X*KucZ&d|r^@>=rfnKoC}a=vtEh%%tA?u&@_IGDT{-%)fE([}QvM/}');
define('NONCE_SALT', 'ISSNRR*kD)QAydVXYD_|rZLVT=+O};yEbgJ>rllqk]]tWA]*$LYmXBIEonrOvIXJY)^L(Ld]>i]pt*&HhlA=dWqkyeIHvxkP[r=Owt*wqOh[(AqL=L$ky^X$ejSskL&t');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_mnyz_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
