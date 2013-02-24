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

**review** - Shows all changes on a particular project since the last branch was created

**prepare** - Creates a new release tag for a particular project

**help** - Shows documentation for other commands

Configuration
=============

To configure relear system-wide, copy the .conf file to /etc/releasr.conf

Repository configuration options
--------------------------------

Special values inside the URL will be replaced, at present the only supported value is %PROJECT% which will be replaced with the name of the current project.

Note: URLs should not be suffixed with a /

**trunk_url** specifies the trunk URL for a project. 

    trunk_url = http://my-server-domain/svn/projects/%PROJECT%/trunk
    
**releases_url** specifies the URL in which a project's tagged releases are kept. 

    releases_url = http://my-server-domain/svn/projects/%PROJECT%/branches/releases

Example workflow
================

Open terminal and type:

    > releasr list myproject
    2 releases found for "myproject":
    -> 2013-06-01
    -> 2013-06-20
    
Check the list of branches on that project to check the branch name format

    > releasr review myproject
    2 changes found:
    ciaranm -> [T-12324] Fixed a bug with the thing
    ciaranm -> [T-24244] Added a new feature

Review the list of changes since the last branch was made and check there aren't any nasty surprises

    > releasr prepare myproject 2013-06-28
    Successfully created branch 2013-06-28

Make a new release tag for that project, then go off and check that out somewhere / svn switch your servers.

Future potential additions
==========================

* Configurable branch name generation
* Freezing of externals on creation of a release branch
* Some sort of pull / deployment?

Why not use Git?
================

I'm writing this for a specific use case that involves SVN. When its done I may do a Git implementation but frankly branch management is easier in Git so might not bother.

