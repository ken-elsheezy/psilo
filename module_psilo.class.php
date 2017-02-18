<?php

//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
    exit;
}

/*
 * Class defining the new module
 * The name must match the one provided in the module.xml file
 */
class module_psilo extends EfrontModule {

	/**
	 * Get the module name, for example "Demo module"
	 *
	 * @see libraries/EfrontModule#getName()
	 */
    public function getName() {
    	//This is a language tag, defined in the file lang-<your language>.php
        return _MODULE_PSILO_MODULEPSILO;
    }

	/**
	 * Return the array of roles that will have access to this module
	 * You can return any combination of 'administrator', 'student' or 'professor'
	 *
	 * @see libraries/EfrontModule#getPermittedRoles()
	 */
    public function getPermittedRoles() {
        return array("administrator","professor");		//This module will be available to administrators
    }

    /**
	 * (non-PHPdoc)
	 * @see libraries/EfrontModule#getCenterLinkInfo()
     */
    public function getCenterLinkInfo() {
    	return array('title' => $this -> getName(),
                     'image' => $this -> moduleBaseLink . 'images/logo.png',
                     'link'  => $this -> moduleBaseUrl);
    }
    
    /**
     * The main functionality
     *
	 * (non-PHPdoc)
	 * @see libraries/EfrontModule#getModule()
     */
    public function getModule() {
    	$smarty = $this -> getSmartyVar();
        $smarty -> assign("T_MODULE_BASEDIR" , $this -> moduleBaseDir);
        $smarty -> assign("T_MODULE_BASELINK" , $this -> moduleBaseLink);
        $smarty -> assign("T_MODULE_BASEURL" , $this -> moduleBaseUrl);
        
        $page = $_GET['page'];
        
        switch ($page) {
            case 'categories':
                $this->doCategories();
                break;
            
            case 'articles':
                $this->doArticles();
                break;
            
            case 'comments':
                $this->doComments();
                break;
            
            case 'files':
                $this->doFiles();
                break;
            
            case 'ratings':
                $this->doRatings();
                break;

            default:
                break;
        }
        
        return true;
    }
    
    public function getModuleCSS() {
        return $this->moduleBaseLink .'module_psilo.css';
    }
    
    public function getModuleJS() {
        return $this->moduleBaseLink .'module_psilo.js';
    }
    
    /**
     * Specify which file to include for template
     *
	 * (non-PHPdoc)
	 * @see libraries/EfrontModule#getSmartyTpl()
     */
    public function getSmartyTpl() {
        
       return $this -> moduleBaseDir."module_psilo_page.tpl";
        
    }

    
    /**
	 * (non-PHPdoc)
	 * @see libraries/EfrontModule#getNavigationLinks()
     */
    public function getNavigationLinks() {
        return array (array ('title' => _HOME, 'link'  => $_SERVER['PHP_SELF']),
                      array ('title' => $this -> getName(), 'link'  => $this -> moduleBaseUrl));
    }
    
    /*
     * Custom methods
     */
    
    public function onInstall() {
        $sql = "
            CREATE TABLE IF NOT EXISTS psilo_categories (
                id INT(11) NOT NULL AUTO_INCREMENT,
                name VARCHAR(250),
                creator_user_type VARCHAR(100),
                creator_LOGIN VARCHAR(250),
                active TINYINT(1) NOT NULL DEFAULT '0',
                date_created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY(id)
            )
                ";
        eF_executeQuery($sql);
        
        $sql = "
            CREATE TABLE IF NOT EXISTS psilo_subcategories (
                id INT(11) NOT NULL AUTO_INCREMENT,
                category_ID INT(11) NOT NULL,
                name VARCHAR(250),
                creator_user_type VARCHAR(100),
                creator_LOGIN VARCHAR(250),
                active TINYINT(1) NOT NULL DEFAULT '0',
                date_created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY(id)
            )
                ";
        eF_executeQuery($sql);
        
        $sql = "
            CREATE TABLE IF NOT EXISTS psilo_articles (
                id INT(11) NOT NULL AUTO_INCREMENT,
                name VARCHAR(250),
                category_ID INT(11) NOT NULL,
                subcategory_ID INT(11),
                metadata TEXT NULL,
                enable_comments TINYINT(1) DEFAULT 1,
                creator_user_type VARCHAR(100),
                creator_LOGIN VARCHAR(250),
                active TINYINT(1) NOT NULL DEFAULT '0',
                date_created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY(id)
            )
                ";
        eF_executeQuery($sql);
        
        $sql = "
            CREATE TABLE IF NOT EXISTS psilo_comments (
                id INT(11) NOT NULL AUTO_INCREMENT,
                comment VARCHAR(600),
                article_ID INT(11) NOT NULL,
                creator_user_type VARCHAR(100),
                creator_LOGIN VARCHAR(250),
                active TINYINT(1) NOT NULL DEFAULT '0',
                date_created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY(id)
            )
                ";
        eF_executeQuery($sql);
        
        $sql = "
            CREATE TABLE IF NOT EXISTS psilo_files (
                id INT(11) NOT NULL AUTO_INCREMENT,
                name VARCHAR(100),
                article_ID INT(11) NOT NULL,
                path VARCHAR(100),
                active TINYINT(1) NOT NULL DEFAULT '0',
                date_created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY(id)
            )
                ";
        eF_executeQuery($sql);
        
        $sql = "
            CREATE TABLE IF NOT EXISTS psilo_ratings (
                id INT(11) NOT NULL AUTO_INCREMENT,
                user_LOGIN VARCHAR(250) NOT NULL,
                weight INT(11) NOT NULL,
                article_ID INT(11) NOT NULL,
                date_created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY(id)
            )
                ";
        eF_executeQuery($sql);
        
        return true;
    }
    
    public function onUninstall() {
        
        eF_executeQuery("DROP TABLE IF EXISTS psilo_categories");
        eF_executeQuery("DROP TABLE IF EXISTS psilo_subcategories");
        eF_executeQuery("DROP TABLE IF EXISTS psilo_files");
        eF_executeQuery("DROP TABLE IF EXISTS psilo_comments");
        eF_executeQuery("DROP TABLE IF EXISTS psilo_articles");
        eF_executeQuery("DROP TABLE IF EXISTS psilo_ratings");
        
        return true;
    }
    
    public function menu(){
        $menu = [
            [
                'text' => 'Users',
                'icon' => 'fa fa-users',
                'href'  => $this -> moduleBaseUrl.'&r=users'
                ],
            [
                'text' => 'Lessons',
                'icon' => 'fa fa-book',
                'link'  => $this -> moduleBaseUrl.'&r=lessons'
                ],
            [
                'text' => 'Courses',
                'icon' => 'fa fa-list-alt',
                'href'  => $this -> moduleBaseUrl.'&r=courses'
                ],
            [
                'text' => 'System',
                'icon' => 'fa fa-dashboard',
                'href'  => $this -> moduleBaseUrl.'&r=courses'
                ],
            
        ];
        
        return $menu;
    }
    
    public function doCategories(){
        $smarty = $this ->getSmartyVar();
        $data = '<h2 style="text-align: center" class="title"> <i class="fa fa-list-ul"></i> Categories </h2>
        <hr/>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Hits</th>
                    <th>Visible</th>
                    <th>Subcats</th>
                    <th>Articles</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>';
        
        $smarty -> assign('T_TABLE',$data);
    }
    
    public function doSubcategories(){
        $smarty = $this ->getSmartyVar();
        $data = '<h2 style="text-align: center" class="title"> <i class="fa fa-files"></i> Subcategories </h2>
        <hr/>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Hits</th>
                    <th>Visible</th>
                    <th>Subcats</th>
                    <th>Articles</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>';
        
        $smarty -> assign('T_TABLE',$data);
    }
    
    public function doArticles(){
        $smarty = $this ->getSmartyVar();
        $data = '<h2 style="text-align: center" class="title"> <i class="fa fa-paperclip"></i> Articles </h2>
        <hr/>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Hits</th>
                    <th>Visible</th>
                    <th>Subcats</th>
                    <th>Articles</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>';
        
        $smarty -> assign('T_TABLE',$data);
    }
    
    public function doComments(){
        $smarty = $this ->getSmartyVar();
        $data = '<h2 style="text-align: center" class="title"> <i class="fa fa-comments"></i> Comments </h2>
        <hr/>
        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Comment</th>
                    <th>Article Name</th>
                    <th>Date and time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>';
        
        $smarty -> assign('T_TABLE',$data);
    }
    
    public function doFiles(){
        $smarty = $this ->getSmartyVar();
        $data = '<h2 style="text-align: center" class="title"> <i class="fa fa-file"></i> Files </h2>
        <hr/>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Hits</th>
                    <th>Visible</th>
                    <th>Subcats</th>
                    <th>Articles</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>';
        
        $smarty -> assign('T_TABLE',$data);
    }
    
    public function doRatings(){
        $smarty = $this ->getSmartyVar();
        $data = '<h2 style="text-align: center" class="title"> <i class="fa fa-star-o"></i> Ratings </h2>
        <hr/>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Hits</th>
                    <th>Visible</th>
                    <th>Subcats</th>
                    <th>Articles</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>';
        
        $smarty -> assign('T_TABLE',$data);
    }
}
