=== Oasis Workflow Pro ===
Contributors: nuggetsol
Tags: workflow, work flow, review, assignment, publish, inbox, workflow history, audit
Requires at least: 3.6
Tested up to: 3.8.1
Stable tag: 1.0.5

Workflow process for WordPress made simple with Oasis Workflow.

== Description ==

Any online publishing organization has one or several Managing Editors responsible for keeping the arrangement of editorial content flowing in an organized fashion.

Oasis Workflow plugin is designed to automate any workflow process using a simple, intuitive graphical user interface (GUI).

The plugin provides three processes:

1. Assignment - represents task related to content generation.

2. Review - represents task related to content review.

3. Publish - represents the actual "publish" task.

**Visual Work flow Designer**
 - Configure your work flow using the easy drag and drop designer interface. See screen shots for more detail.

**Role-Based routing definitions allow you to assign tasks dynamically**
 - By using role-based routing, you can ensure that your process moves forward as quickly as possible without sacrificing accountability.

**Inbox**
 - Users can view their current assignments and sign off their tasks once it's completed.

**Process history lets users retrace their steps**
 - For auditing purposes a record is maintained of all posts that are routed through a workflow process. The process history also captures the comments added by the user when they signed off the particular task.

**Reassign - How to pass the buck?**
 - What if you have been assigned a workflow task, but you feel you are not the appropriate person to complete it? No worry, you can assign the task to another person. 

**Due Date and Email reminders** help you to publish your articles on time.

**Out of the box workflow**
To get you started, the plugin comes with an out of the box workflow. You can also modify the workflow to suit your needs. 

You can find the complete list of features on the [support](http://oasisworkflow.com) site.

**Supported languages**
 - English
 - Spanish
 - French
 - German 
 
**Translators**
* German (de_DE) - [meganlop](http://profiles.wordpress.org/meganlop)
* French (fr_FR) - [Baptiste Rieg](http://www.batrieg.com)

**If you need help setting up the roles, we recommend the [User Role Editor plugin](http://wordpress.org/extend/plugins/user-role-editor/ "User Role Editor plugin").**

Videos to help you get started with Oasis Workflow:

[youtube http://www.youtube.com/watch?v=PPBJns2p-zU]

[youtube http://www.youtube.com/watch?v=SuOCBf_mLpc]

== Installation ==

1. Download the plugin zip file to your desktop
2. Upload the plugin to WordPress
3. Activate your license by going to Workflow Admin --> Settings --> General Settings
4. Activate Oasis Workflow by going to Workflow Admin --> Settings --> Workflow Settings 
5. You are now ready to use Oasis Workflow! Build Your Workflow and start managing your editorial content flow.

== Frequently Asked Questions ==

For [Frequently Asked Questions](http://oasisworkflow.com/faq) plus documentation, plugin help, go [here](http://oasisworkflow.com)

== Screenshots ==

1. Visual Work flow designer
2. Role-based routing
3. Inbox
4. Sign off
5. Process history


== Changelog ==

= Version 1.0.0 =

Initial Pro version


= Version 1.0.1 =
* Added copy workflow and copy step functionality.
* Visual indication of the first step on the workflow in light blue color.

= Version 1.0.2 =
* Added workflow support for updating published content.
* made publish step a multi-user assignment step with claim process.
* fixed issue with permalink being changed after publish from the inbox page.
* fixed the issue with unnecessary call to post_publish hook.
* after sign off, the user will be redirected to the inbox page.

= Version 1.0.3 =
* fixed the "make revision" functionality
* fixed to remove a warning message related to mysql_real_escape_string() 

= Version 1.0.4 =
* major fixes for supporting "workflow for published content".
* added two new hooks for updating published content via workflow.
* added german translation files
* fixed the issues with Strict PHP - non static function called in static fashion
* fixed update datetime issue with the workflow
* changed post title to be a simple text in the subject line   

= Version 1.0.5 =
* fixed issue with workflow history discrepancies and abort workflow action.
* fixed meta copy function for revise action.
* fixed DB related issues with NULL and NOT NULL.
* fixed multisite issue related to switch and restore blog.

= Version 1.0.6 =
* Load the JS and CSS scripts only when needed. This helps with compatibility issues with other plugins.
* Allow setting of future publish date on submit to workflow.
* Allow setting a workflow for "New" and/or "Revised" posts/pages.
* Multi-Abort functionality.
* Added additional placeholders for email.
* Workflow Process can now be selectively applied to certain post types.
* Email author when the post is published.
* fixed german translations.
* fixed compatibility issues with Wordpress 3.9