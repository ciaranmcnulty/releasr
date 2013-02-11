A simple release tool for those using SVN who want to have:

1. A trunk branch
2. A set of release tags
3. The ability to see what's happened on trunk since the last tag was made
4. The ability to prepare a new release tag with a single command
5. Some sort of deployment (maybe) 
6. Multiple projects in the same repo with the above setup

Example workflow
----------------

    releasr list myproject
    
Displays list of release tags for that project

    releasr review myproject
    
(not implemented) Shows the log of changes on trunk, and any that have happened on the branch

    releasr prepare myproject
    
(not implemented) Makes a new release tag in the configure naming scheme, sorts out any externals

Why not use Git?
----------------

I'm writing this for a specific use case that involves SVN. When its done I may do a Git implementation but frankly branch management is easier in Git so might not bother.
