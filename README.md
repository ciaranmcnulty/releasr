Intro
=====

Releasr is a simple release tool for those using SVN who want to have:

1. A trunk branch
2. A set of release tags
3. The ability to see what's happened on trunk since the last tag was made
4. The ability to prepare a new release tag with a single command
5. Easy management of multiple projects with the above setup

Available commands
==================

**list** - Shows all of the branches for a particular project

**latest** - Shows details of the latest release for a particular project

**review** - Shows all changes on a particular project since the last branch was created

**prepare** - Creates a new release tag for a particular project

**help** - Shows documentation for other commands

Installation
============

Git clone from this repo to a path of your choosing, e.g.:

    git clone <repo>.git /opt/releasr

Symlink releasr.php into the system path

    ln -s /opt/releasr/releasr.php /usr/bin/releasr
    
Either edit the config file in place or better, move it to /etc/releasr.conf

    cp /opt/releasr/config/releasr.conf /etc/releasr.conf
    
Modify the configuration file to suit your repository.

Configuration
=============

Config file
-----------

Releasr will search the following locations for a configuration

1. The location specified in the $RELEASR\_CONFIG environment variable
2. The system location /etc/releasr.conf
3. config/releasr.conf inside the install location

Repository URLs
---------------

Special values inside URLs will be replaced, at present the only supported value is %PROJECT% which will be replaced with the name of the current project.

Note: URLs should not be suffixed with a /

**trunk_url** specifies the trunk URL for a project. 

    trunk_url = http://my-server-domain/svn/projects/%PROJECT%/trunk
    
**releases_url** specifies the URL in which a project's tagged releases are kept. 

    releases_url = http://my-server-domain/svn/projects/%PROJECT%/branches/releases
    
Project options
---------------

Optionally specify a list of allowed projects. Releasr will show an error if a project name not on the list is used

    projects = project1, project2, project3

svn:externals handling
==================

Currently the prepare command will warn the user if there are any externals on newly created release branch:

    Warning - unversioned externals exist on branch at:
    file:///myrepo/myproject/releases/mybranch/library

The best option for resolving this is to set the svn:externals property on trunk and then re-run whatever testing you would normally do to make sure a library update has not broken your code.

It's possible that a future version of Releasr will automate this.

Example workflow
================

Check the list of releases on that project:

    > releasr list myproject
    2 releases found for "myproject":
    -> 2013-06-01
    -> 2013-06-20

Review the list of changes since the last release was made and check there aren't any surprises:

    > releasr review myproject
    2 changes found:
    ciaranm -> [T-12324] Fixed a bug with the thing
    ciaranm -> [T-24244] Added a new feature

Make a new release tag for that project, then go off and check that out somewhere / svn switch your servers.

    > releasr prepare myproject 2013-06-28
    Successfully created branch 2013-06-28

Future potential additions
==========================

* Configurable branch name generation
* Freezing of externals on creation of a release branch
* Some sort of pull / deployment?

Why not use Git?
================

I'm writing this for a specific use case that involves SVN. When its done I may do a Git implementation but frankly branch management is easier in Git so might not bother.

