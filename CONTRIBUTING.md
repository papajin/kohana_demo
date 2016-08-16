# Developing locally

Clone the repo and develop on the Master branch. Each release will have its own Tag.

I know its simple but for now will work ;)


# Contributing to the project

All features and bugfixes must be fully tested and reference an issue in  [GitHub](https://github.com/koseven/koseven/issues), **there are absolutely no exceptions**.

It's highly recommended that you write/run unit tests during development as it can help you pick up on issues early on.  See the Unit Testing section below.

## Creating new features

New features or API breaking modifications should be developed in separate PR so as to isolate them
until they're stable.

**Features without tests written will be rejected! There are NO exceptions.**

The naming convention for feature branches is:

  {version}/feature/{issue number}-{short hyphenated description}
  
  // e.g.

  3.2/feature/4045-rewriting-config-system
  
When a new feature is complete and fully tested it can be merged into its respective release branch using
`git pull --no-ff`. The `--no-ff` switch is important as it tells Git to always create a commit
detailing what branch you're merging from. This makes it a lot easier to analyse a feature's history.

Here's a quick example:

  > git status
  # On branch 3.2/feature/4045-rewriting-everything
  
  > git checkout 3.1/develop
  # Switched to branch '3.1/develop'

  > git merge --no-ff 3.2/feature/4045-rewriting-everything

**If a change you make intentionally breaks the API then please correct the relevant tests before pushing!**

## Bug fixing 
Make a PR with the fix, explain as in detail as possiblle.

## Tagging releases

Tag names should be prefixed with a `v`, this helps to separate tag references from branch references in Git.

For example, if you were creating a tag for the `3.1.0` release the tag name would be `v3.1.0`

# Merging changes from remote repositories

Now that you have a remote repository, you can pull changes in the remote "koseven" repository
into your local repository:

    > git pull koseven 3.1/master

**Note:** Before you pull changes you should make sure that any modifications you've made locally
have been committed.

Sometimes a commit you've made locally will conflict with one made in the remote "koseven" repo.

There are a couple of scenarios where this might happen:

## The conflict is due to a few unrelated commits and you want to keep changes made in both commits

You'll need to manually modify the files to resolve the conflict, see the "Resolving a merge"
section [in the Git SCM book](http://book.git-scm.com/3_basic_branching_and_merging.html) for more info

## You've fixed something locally which someone else has already done in the remote repo

The simplest way to fix this is to remove all the changes that you've made locally.

You can do this using 

    > git reset --hard koseen

## You've fixed something locally which someone else has already fixed but you also have separate commits you'd like to keep

If this is the case then you'll want to use a tool called rebase.  First of all we need to
get rid of the conflicts created due to the merge:

    > git reset --hard HEAD

Then find the hash of the offending local commit and run:

    > git rebase -i {offending commit hash}

i.e.

  > git rebase -i 57d0b28

A text editor will open with a list of commits. Delete the line containing the offending commit
before saving the file & closing your editor.

Git will remove the commit and you can then pull/merge the remote changes.

# Unit Testing

Koseven currently uses PHPUnit for unit testing. This is installed with composer.

## How to run the tests

 * Install [Phing](http://phing.info)
 * Make sure you have the unittes module enabled.
 * Install [Composer](http://getcomposer.org)
 * Run `php composer.phar install` from the root of this repository
 * Finally, run `phing test`

This will run the unit tests for core and all the modules and tell you if anything failed. If you haven't changed anything and you get failures, please create a new issue on  and paste the output (including the error) in the issue.  
