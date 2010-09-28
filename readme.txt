=== BP Lotsa Feeds ===
Contributors: boonebgorges, redroverhq 
Donate link: http://teleogistic.net/donate
Tags: buddypress, feed, feeds, rss, activity
Requires at least: WP 3.0, BP 1.2.5
Tested up to: WP 3.0.1, BP 1.2.5.2
Stable tag: 1.0

Gives your BuddyPress installation lotsa feeds.

== Description ==

BP Lotsa Feeds adds a whole bunch of RSS feeds to your installation of BuddyPress. The following feeds are included with BP Lotsa Feeds (followed by the URL pattern where the feeds can be found):

INDIVIDUAL MEMBERS
*	Networkwide comments by an individual member (/members/username/activity/comments/feed)
*	Networkwide blog posts by an individual member (/members/username/activity/blogposts/feed)
*	Activity updates by an individual member (/members/username/activity/updates/feed)
*	An individual member's friendship connections (/members/username/activity/friendships/feed)
*	Forum topics started by an individual member (/members/username/activity/forumtopics/feed)
*	Forum replies by an individual member (/members/username/activity/forumreplies/feed)
*	All forum activity by a member (a combination of the previous two feeds) (/members/username/activity/forums/feed)

INDIVIDUAL GROUPS
*	A group's activity updates (/groups/groupname/updates/feed)
*	New forum topics in a given group (/groups/groupname/forumtopics/feed)
*	Forum replies in a given group (/groups/groupname/forumreplies/feed)
*	All forum activity in a given group (a combination of the previous two feeds) (/groups/groupname/forums/feed)
*	A group's new members (/groups/groupname/membership/feed)

FORUMS
*	Individual forum topic posts (/groups/groupname/forum/topic/topicslug/feed)

You can make any of these feeds inaccessible by defining the corresponding DISABLE constant in plugins/bp-custom.php or wp-config.php, e.g.
`define( 'BPLF_DISABLE_GROUP_UPDATES_FEED', true )`
See `bp-lotsa-feeds.php` for the proper constant names.

Hooks and filters are in place so that you can add custom feeds and feed templates.

Follow the plugin's development at http://github.com/boonebgorges/bp-lotsa-feeds


== Installation ==

1. Activate the plugin from the plugins screen
1. That's it

== Changelog ==

= 1.0 =

* Initial release
