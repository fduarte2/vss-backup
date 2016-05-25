<?php
    //
    //  $Id: config.php,v 1.47.2.3.4.1 2003/11/09 14:41:13 wk Exp $
    //

    $config->applVersion = '2.1.0-RC1';
    $config->applName = 'v<span class="vpRedDotColor">:</span>p openTimetool  v'.$config->applVersion;
    
    /**
    * Where to find the includes. Includes are all the external 
    * classes and libraries, such as PEAR-packages etc.
    * 
    */
    $config->includePath = dirname(__FILE__).'/includes';
    
    // this message comes up because we store our session-vars in the global space :-(
    ini_set('session.bug_compat_warn',0);
    // set it to 0 when you have a productive system
    ini_set('display_errors',0);
    ini_set('magic_quotes_gpc',0);
    ini_set('max_execution_time',1000);

    /**
    * The DB DSN, as needed for PEAR::DB.
    *
    * @var string the DB connection parameters
    */
    $config->dbDSN              = 'mysql://root@localhost/openTimetool';
    
    /**
    * This is the path to html2pdf, this application is needed
    * when you want to export your timesheets to pdf.
    * @var string the path to html2pdf
    */
    $config->html2pdf = '/usr/local/bin/html2pdf $1 $2';
                        
    /**
    * Automatically determined!
    * This is the prefix of the path that leads to this application via a user agent.
    * I.e. if the application is reachable under this domain:
    *   http://www.myhost.com/myApps/timetool
    * then this variable would need to be '/myApps/timetool'
    * If the application runs directly on a virtual host, this string would need
    * to be empty ''
    * 
    * @var string the application path prefix
    */
//    $config->applPathPrefix     = '/openTimetool';
    
    
    /**
    * Automatically determined!
    * This is the 
    *
    *
    */
//    $config->applRoot           = $_SERVER['DOCUMENT_ROOT'].$config->applPathPrefix.'/htdocs';
    
//    $config->finalizePage       = $_SERVER['DOCUMENT_ROOT'].'/htdocs/finalize.php';

    /**
    * If there are additional things that need to be included, 
    * define the paths here.
    * @var string 
    */
    $config->includePath        = $config->includePath;

    /**
    * The various authentication parameters. This application can also
    * use an authentication against an external source, this you can configure here.
    * Please further down for more info ...
    * 
    */
    $config->auth->method       =   'DB';
    $config->auth->url          =   $config->dbDSN;
    $config->auth->digest       =   'md5';
    $config->auth->savePwd      =   true;    

    /** 
    * actually you can have different run modes, which allow you to configure the 
    * application once and run it in different environments, but i removed that for the
    * first openSource version, just to make things easier :-)
    * So dont change this here
    */
    $config->runMode = 'live';

/*                        
    // on the following lines you will find additional config options, that
    // can be set for the application, just choose what you need and put it in the
    // outside the commented part :-)
    
    // set the path which included always, like for PEAR, etc.
    $config->includePath        =   dirname(__FILE__).'/includes_cvs'.':'.$config->includePath;

    // DEFAULT CONFIG
    $config->dbDSN              =   'mysql://root@localhost/timetool';

    //
    // ASP CONFIG
    //
    // If you want to run this application as an ASP version, which 
    // means the authentication and control of the number of allowed users and
    // so on is retreived from an external application, then you would need to 
    // use one of the following parts to configure this. But this is too proprietary
    // currently, since you would also need the external application, which v:p
    // has not released as opensource. For further information please contact
    // v:p directly.
    // 
    $config->dbDSN              =   'mysql://root@localhost/';

    $config->backOffice->host   =   'localhost';
    $config->backOffice->path   =   '/timetool_admin/htdocs/modules/remote/xmlrpc.php';
    $config->backOffice->port   =   443;

    $config->backOffice->host   =   'www.timetool.biz';
    $config->backOffice->path   =   '/admin/modules/remote/xmlrpc.php';
    $config->backOffice->port   =   443;
    $config->backOffice->authUser = 'username';
    $config->backOffice->authPassword = 'password';


    $config->dbDSN              =   'mysql://root@localhost/timetool_asp';
    $config->backOffice->host   =   'ashley.unix.vp';
    $config->backOffice->path   =   '/timetool_admin/htdocs/modules/remote/xmlrpc.php';
    $config->backOffice->authUser = '';
    $config->backOffice->authPassword = '';

    $config->html2pdf = '/usr/local/bin/html2pdf $1 $2';

    //
    // Here you can see different Auth methods.
    // DB -  is the default mode, which also stores the password in the openTimetool
    // database, all the others use an external auth method and the password is 
    // not stored in the timetool DB!
    // You can determine if the password is stored in the DB by setting the 
    // options "savePwd" accordingly.
    //
    // Those are all just for authentication, the user itself HAS TO BE 
    // added within the timetool too, only that you dont have to maintain
    // her credentials in the openTimetool application, just the reference 
    // to the Auth mode you choose.
    // For more info on how to form the auth-url see the sf.net/projects/auth package
    // or simply try it, its not that difficulty :-)
    //
    
    $config->auth->method       =   'IMAP';
    $config->auth->url          =   'pop3/notls://your.host.com:110';
    $config->auth->digest       =   'none';
    $config->auth->savePwd      =   false;

    $config->auth->method       =   'DB';
    $config->auth->url          =   $config->dbDSN;
    $config->auth->digest       =   'md5';
    $config->auth->savePwd      =   true;

    $config->auth->method       =   'IMAP';
    $config->auth->url          =   'pop3/notls://your.host.com:110';
    $config->auth->digest       =   'none';
    $config->auth->savePwd      =   true;

    ini_set('error_reporting',E_ALL);
*/    

    $config_local = dirname(__FILE__).'/config-local.php';
    if (is_file($config_local)) {
        include_once $config_local;
    }

    require_once 'init.php';

?>
