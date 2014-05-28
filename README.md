					
  ####Genealogy Manager 
  ===================
		
  OVERVIEW
  
  The Geanealogy Manager (GMGR) application is a web-based software composed of the Pedigree Import
  tool and the Pedigree Viewer tool. 
  
  The Pedigree Import tool was created to help bulk load or import breeders' cross histories into the
  GMS database. This tool aims to make it easier, on a web-based platform, display germplasm origin
  information and other names and attributes for the germplasm. 
  
  The Pedigree Viewer is a we-based tool which aims to display a diagram and/or a graphical 
  representation of germplasm relationships.
  
  The GMGR application accesses the Integrated Breeding Program Databases 
  (IBP) through the Middleware API, which consists of managers corresponding to 
  different systems. In order to reuse the methods of the IBP middleware, RESTful
  web services in java were created using the Jersey toolkit, an open source framework
  for developing RESTful Web Services in Java. 
  
  In a Representational State Transfer (REST) architechtural style, data and functionality are 
  considered resources, and these resources are accessed using Uniform Resource Identifiers (URIs), 
  typically links on the web.
  
  You can learn more about the Jersey RESTful Web Services in Java at https://jersey.java.net/
 
  FEATURES
  
  - Easy to Install

	GMGR is built to make it as easy as possible to install open source software. 
	The installers completely automate the process of installing and configuring all of the software 
	needed for the application, so you can have everything up and running in just a few clicks.
  
  - Independent

	GMGR is completely self-contained, and therefore do not interfere
	with any software already installed on your local machine. For example, you can
	upgrade your system's Apache Tomcat without fear of 'breaking' your
	GMGR application.
	
  - Integrated

	By the time you click the 'finish' button on the installer, the whole stack
	will be integrated, configured and ready to go. 
	
  REQUIREMENTS
  
  - To install the GMGR application you will need:
  
    ** Apache installer (XAMPP)
	** Java 6 JDK
	
  DOWNLOAD
  
  - The GMGR (.zip) package, contains the Apache installer, the JDK installer and the GMGR 
    application installer can be downloaded from
    
	** http://23.23.218.31/documentation/index.php/for-users/2-uncategorised/55-download-gmanager.
  
  INSTALLATION
  
  - The GMGR application is distributed as a compressed (zip) package containing the executable version of the 
    project, apache for tomcat, and SQL dump files for the MySQL database. It also contains the
	Pedigree Importer and the Pedigree Viewer web-based application.
	
	It can be downloaded from: 
	
	** http://http://23.23.218.31/documentation/index.php/for-users/2-uncategorised/55-download-gmanager, 
	
  - You can unpack the downloaded package using a decompression tool (i.e. WinRAR or &-zip).
  
	It can be downloaded from:
	
	** http://www.win-rar.org
	** http://www.7-zip.org/
	
	
  - The downloaded installer will be named something similar to:
    
	GMGR-Installer.zip
	
  - To use the software, the following must e properly installed and configured:
  
	** Apache (XAMPP)
	   - Open the Apache-Installer folder
	   - Execute install-apache
	   
	** Java 6 JDK
	   - Open the JDK-Installer folder
	   - Access you environment variables and set/add the following:
	   
		 set JAVA_HOME=“C:\Program Files (x86)\Java\jdk1.6.0_10” 
		 set JRE_HOME=%JAVA_HOME%\jre 
		 set CLASSPATH=%JAVA_HOME%\bin;%CLASSPATH% 
		 set PATH=%JAVA_HOME%\bin;%JRE_HOME%\bin;%PATH% 
		 
    ** Make sure MySQL process is up and running.
	
  USING THE APPLICATION
  
  - To enter to your application you can point your browser (preferrably latest versions of 
    Mozilla Firefox and/or Google Chrome) to
	
	** http://localhost/GMGR
	
	This loads the login page wherein a sample local database is set up during installation.
	To use other and/or existing database refer to DATABASE CONFIGURATION section.
	
  DATABASE CONFIGURATION
  
  - On the login page, click the link 'Database Configuration'
  
  - For both local and central databases specify the following:
  
	** host                (default is localhost)
	** database name       (default is gmgr_local)
	** MySQl port number   (default is 3306)
	** username            (default is gmgruser)
	** password            (default is gmgrpass)

  CONTACTS
  
  - For more information, please contact the developers:  
    **  Joanie C. Antonio <j.antonio@irri.org>,
    **  Nikki G. Carumba <n.carumba@irri.org>,
    **  Kelly John D. Mahipus <k.mahipus@irri.org>
		
  LICENSES

  - Jersey is dual licensed under 2 OSI approved licenses:
    ** COMMON DEVELOPMENT AND DISTRIBUTION LICENSE (CDDL - Version 1.1)
    ** GNU General Public License (GPL - Version 2, June 1991) 

  - Apache Tomcat Server
    ** All software produced by The Apache Software Foundation or any of 
	   its projects or subjects is licensed according to the terms of 
	   Apache License, Version 2.0 (current).

  - PHP and related libraries are distributed under the PHP License v3.01,
	which is located at http://www.php.net/license/3_01.txt
	
  - curl is distributed under the Curl License, which is located at
	http://curl.haxx.se/docs/copyright.html