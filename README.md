CakePHP ZoneAcl Plugin
===================


CakePHP ZoneAcl plugin takes a little different approach to handling permissions in your CakePHP application. ZoneAcl lets you divide your application contollers/actions into groups, called zones.
After you defined your arbitrary zones, you decide which users or groups get permission to which zones on any criteria you like. It provides simplicity and flexibility to your application acl.

----------


Requirement
-------------
 - CakePHP 2.x 

Installation
-------------

#### Method 1:

 1. Download this: http://github.com/pranaya/cakephp-zone-acl/zipball/master
 2. Unzip content into app/Plugin/ZoneAcl

#### Load the Plugin
At app/bootstrap.php

    CakePlugin::load('ZoneAcl', array('bootstrap' => true));


Usage
-------------
#### Configurations
At app/Config/core.php

Change

    Configure::write('Acl.classname', 'DbAcl');
    Configure::write('Acl.database', 'default');

To

    Configure::write('Acl.classname', 'ZoneAcl.ZoneAcl');
    //Configure::write('Acl.database', 'default');

Then copy **app/Plugin/ZoneAcl/Config/zone-acl.ini** file to **app/Config/zone-acl.ini**

If you want to use the ZoneAcl.ZoneAclHtml Helper.

At app/Controller/AppController.php

    <?php
    class AppController extends Controller {
    	public $helpers = array('ZoneAcl.ZoneAclHtml');
    }

#### Setting up zones
Zones are defined in an **ini** file. Defining zones and adding urls (controller/action) to it is simple.

Zones are denoted by "**[zone:zonename]**". You can add as many zones as you like as long as their names do not match. Please use array key friendly zone names.

One url can be in several zones if you like. Urls in the zones can be defined with regular expressions (recommended) or simply the whole controller/action combination.

**Url format**

 - Contoller/action
 - Plugin/Controller/action

You must use the actual controller and action names i.e. use **UserProfiles** not user_profiles for UserProfilesController and use **view_detail** for view_detail action.
Urls are case-sensitive by default, which can be turned off by changing the “**case-sensitive**” setting to **false**. Please refer to the **zone-acl.ini** file for more useful examples of urls.

Example:
**app/Config/zone-acl.ini**

    [settings]
    case-sensitive = true
    
    [zone:admin]
    ; allow all admin_ actions
    url[] = '(\w)+/admin_*'

    ; allow all plugin's admin_ action
    url[] = '(\w+)/(\w+)/admin_*'

    ; allow UserProfile controller's view_detail action, just an example
    url[] = 'UserProfile/view_detail'
    
    [zone:general-user]
    ; allow all actions except admin_ actions
    url[] = '(\w+)/(?!admin_)*'
    
    ; allow all plugin actions except admin_ actions
    url[] = '(\w+)/(\w+)/(?!admin_)*'


#### Setting up Aro
Setting up an Aro is easy. It is basically identifying a user with the session data and determining which zones they can access. The determination part is totally up to your application's need. It can be as simple or as complex as one needs. The Aro can be any object implementing the **ZoneAroInterface**.

Example:

    <?php 
    
    App::uses('ZoneAroInterface', 'ZoneAcl.Controller/Component/Acl');
    
    class MySimpleAro implements ZoneAroInterface { 
    
    	public function getAllowedZones($aro) { 
    		// $aro contains user's session data 
    		$userId = $aro['User']['id']; 
    		$groupId = $aro['User']['group_id']; 
    		 
    		// find what zones user can access with those info 
    		// you can even store the what zones the 
    		// user can access in the session itself 
    		$zones = array(); 
    		 
    		$zones[] = 'admin'; 
    		$zones[] = 'general-user'; 
    		
    		// return array of zone names 
    		//the user can access
    		return $zones; 
    	} 
    }
Once your Aro is ready, plug that Aro to ZoneAcl at AppController::beforeFilter().

Example:

    <?php 
    
    App::uses('MySimpleAro', 'Path/To/Class'); 
    
    class AppController extends Controller { 
    	 
    	public function beforeFilter() { 
    		// plug ARO object
    		ZoneAcl::setAro(new MySimpleAro()); 
    	} 
    }

#### ZoneAclHtml Helper
ZoneAclHtml Helper provides a convenient way of hiding disallowed links. It extends the HtmlHelper. It provides 2 methods.

Any view file:

    <?php
    
    // returns true if the current user is 
    // allowed on that url
    $this->ZoneAclHtml->isAllowed($url); 
    
    // same as HtmlHelper::link() except it returns empty link
    // when user is not allowed
    $this->ZoneAclHtml->link($title, $url); 

Tips
------------

 - Use regular expression often for optimized performance and to reduce the number of urls in the ini file
 - Put the urls that have high traffic on top of the list, so that it is matched faster
 - Seperate a controller's long list of actions into serveral urls for easier readability of your settings
 
License
------------
The MIT License (MIT)

Copyright (c) 2015 Pranaya

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

