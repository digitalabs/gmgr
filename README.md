# **Genealogy Manager for Rice Nomenclature** #
__________________________
		
  **OVERVIEW**
  
  The Genealogy Manager (**_GMGR_**) application is a web-based software composed of the _Pedigree Import_
  tool and the _Pedigree Viewer_ tool developed for databases that follow Rice Nomenclature. 
  
  The _Pedigree Import_ tool was created to aid the bulk loading or importing of breeders' cross histories into the
  **IRIS GMS** database. This tool aims to make it easier, on a web-based platform, to display germplasm origin,
  names, and attributes for the germplasm. 
  
  The _Pedigree Viewer_ is a web-based tool which displays a diagram and/or a graphical 
  representation of germplasm relationships.
  
  The **_GMGR_** application accesses the Integrated Breeding Program Databases for Rice
  (_IBP_) through the Middleware API. In order to reuse the methods of the IBP middleware, RESTful
  web services in java were created using the Jersey toolkit, an open source framework
  for developing RESTful Web Services in Java. 
  
  In a Representational State Transfer (_REST_) architechtural style, data and functionality are 
  considered resources, and these resources are accessed using Uniform Resource Identifiers (_URIs_), 
  typically links on the web.
  
  You can learn more about the Jersey RESTful Web Services in Java **[here](https://jersey.java.net). **
 
  **FEATURES**
  
  - GUI-based installation

	With **_GMGR_** installer it is possible to install open source software. 
	The installers automates the process of installing and configuring the software 
	needed for the application. 
	
  
  - Independent

	**_GMGR_** is self-contained, and therefore do not interfere
	with any software already installed on your local machine. For example, you can
	upgrade your system's Apache Tomcat without fear of 'breaking' your
	**_GMGR_** application.
	
	
  - Integrated

	By the time you click the 'finish' button on the installer, the whole stack
	will be integrated and configured. 
	
  **REQUIREMENTS**
  
  - To install the **_GMGR_** application you will need:
  
    1. Apache installer (_XAMPP_)
	2. Java 6 JDK
	
  **DOWNLOAD**
  
  - The GMGR (.zip) package, contains the Apache installer, the JDK installer and the GMGR 
    application installer can be downloaded **[here](http://23.23.218.31/documentation/index.php/for-users/2-uncategorised/55-download-gmanager). **
  
  **INSTALLATION**
  
  - The **_GMGR_** application is distributed as a compressed (_zip_) package containing the executable version of the 
    project, apache for tomcat, and SQL dump files for the MySQL database. It also contains the
	_Pedigree Importer_ and the _Pedigree Viewer_ web-based application.
	
	It can be downloaded **[here](http://http://23.23.218.31/documentation/index.php/for-users/2-uncategorised/55-download-gmanager). **
	
	
  - You can unpack the downloaded package using a decompression tool (i.e. WinRAR or &-zip).
  
	It can be downloaded **[here](http://www.win-rar.org) ** and **[here](http://www.7-zip.org/). **
	
	
  - The downloaded installer will be named something similar to:
    
	> GMGR-Installer.zip
	
  - To use the software, the following must be properly installed and configured:
  
	1. Apache (_XAMPP_)
	   * Open the Apache-Installer folder
	   * Execute install-apache
	   
	2. Java 6 JDK
	   * Open the JDK-Installer folder
	   * Access you environment variables and set/add the following:

           > * set JAVA_HOME=“C:\Program Files (x86)\Java\jdk1.6.0_10”
	       > * set JRE_HOME=%JAVA_HOME%\jre
	       > * set CLASSPATH=%JAVA_HOME%\bin;%CLASSPATH%
	       > * set PATH=%JAVA_HOME%\bin;%JRE_HOME%\bin;%PATH%

       3. Make sure MySQL process is up and running.
	
  **USING THE APPLICATION**
  
  - To enter to your application you can point your browser (preferrably latest versions of 
    Mozilla Firefox and/or Google Chrome) to
	
	> http://localhost/GMGR
	
	This loads the login page wherein a sample local database is set up during installation.
	To use other and/or existing database refer to _DATABASE CONFIGURATION_ section.
	
  **DATABASE CONFIGURATION**
  
  - On the login page, click the link '_Database Configuration_'
  
  - For both local and central databases specify the following:
  
	* host                (default is _localhost_)
	* database name       (default is _gmgr_local_)
	* MySQl port number   (default is _3306_)
	* username            (default is _gmgruser_)
	* password            (default is _gmgrpass_)

  - The database connection settings are configured in GMGR/json_files/database. json file and on the local storage. 
    If the file is not present/empty and the database settings stored in the local storage is empty, the automatic database 
    to use is the “ iris_mysiam_20121002” in the vm-mugi server.

  - How to configure the Database (GMGR)
    
    1. Click the link “check database settings” found in the login data-browser.              
    2. Confirm that the host, database and port name for both local and central are correct.
    3. Enter the database username and password.
    4. Make sure the fields contain the correct settings. Click the “submit” button to create/configure the database connection.
		
  **LICENSES**

  - _Jersey_ is dual licensed under 2 OSI approved licenses:
    * COMMON DEVELOPMENT AND DISTRIBUTION LICENSE (CDDL - Version 1.1)
    * GNU General Public License (GPL - Version 2, June 1991) 

	
  - Apache Tomcat Server
    * All software produced by The Apache Software Foundation or any of 
	   its projects or subjects is licensed according to the terms of 
	   Apache License, Version 2.0 (current).
	   

  - PHP and related libraries are distributed under the PHP License v3.01,
	which is located [here](http://www.php.net/license/3_01.txt)
	
	
  - _curl_ is distributed under the Curl License, which is located [here](http://curl.haxx.se/docs/copyright.html)