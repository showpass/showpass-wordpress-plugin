# Showpass Events API plugin - Releases

## 1. Make .zip file for plugin (ready for install)

As you know, you can instal through plugin installer or you can extract the plugin folder in `wp-content/plugins`.

For our plugin we need only `plugin` folder and all scripts are inside.

*please manually update the version number in showpass-wordpress-plugin.php*

For preparing .zip file for plugin you will need to do this:

* You will have commited and pushed on master branch all the changes that you made.

* Call this command `git archive -o showpass-wordpress-plugin.zip --prefix=showpass-wordpress-plugin/ HEAD:plugin`           

It will create `showpass-wordpress-plugin.zip` file and it will be prepared for install through the plugin installed. Inside this .zip file are all folders and files that the plugin needs for working.

* If you want you can change name of .zip files acording to version of plugin          
`git archive -o showpass-wordpress-plugin.v1.0.2.zip --prefix=showpass-wordpress-plugin/ HEAD:plugin` , or similar. You can name it whatever you want.

* after that you will need to add the modification `git add .` and to commit it `git commit -m "New .zip file for plugin"`

* and for the final `git push origin master` ... user and password.



## 2. Release

* On GitHub, navigate to the main page of the repository.

* Under your repository name, click Releases.

* Click Draft a new release..

* Type a version number for your release. Versions are based on Git tags. We recommend naming tags that fit within semantic versioning.

* Select a branch that contains the project you want to release. Usually, you'll want to release against your master branch, unless you're releasing beta software.

* Type a title and description that describes your release.

* If you'd like to include binary files along with your release, such as compiled programs, drag and drop or select files manually in the binaries box.

* If the release is unstable, select This is a pre-release to notify users that it's not ready for production.

* If you're ready to publicize your release, click Publish release. Otherwise, click Save draft to work on it later.
